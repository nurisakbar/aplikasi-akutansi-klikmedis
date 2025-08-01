<?php

namespace App\Http\Controllers;

use App\Models\AccountancyChartOfAccount;
use App\Http\Requests\StoreChartOfAccountRequest;
use App\Http\Requests\UpdateChartOfAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use App\Exports\ChartOfAccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ChartOfAccountController extends Controller
{
    /**
     * Get company ID from authenticated user.
     */
    protected function getCompanyId(): string|null
    {
        if (!Auth::check()) {
            abort(401, 'User tidak terautentikasi');
        }

        $user = Auth::user();

        // Superadmin bisa melihat semua data
        if ($user->hasRole('superadmin')) {
            return null;
        }

        if (!$user->accountancy_company_id) {
            abort(403, 'User tidak terkait dengan perusahaan manapun');
        }

        return $user->accountancy_company_id;
    }

    /**
     * Display a listing of chart of accounts.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            // Handle parent_only request for modal dropdown
            if ($request->has('parent_only')) {
                $companyId = $this->getCompanyId();
                $query = AccountancyChartOfAccount::query();

                // Filter by company_id if not superadmin
                if ($companyId) {
                    $query->getByCompanyId($companyId);
                }

                $accounts = $query->active()
                    ->orderBy('code')
                    ->get(['id', 'code', 'name']);

                return response()->json(['data' => $accounts]);
            }

            \Log::info('AJAX request received for chart of accounts');
            return $this->getDataTableResponse($request);
        }

        \Log::info('View request received for chart of accounts');
        return view('chart_of_accounts.index');
    }

    /**
     * Get DataTables response for chart of accounts.
     */
    private function getDataTableResponse(Request $request): JsonResponse
    {
        try {
            $companyId = $this->getCompanyId();

            // Debug logging
            \Log::info('DataTables request', [
                'company_id' => $companyId,
                'user' => Auth::user()->name,
                'user_company' => Auth::user()->accountancyCompany ? Auth::user()->accountancyCompany->name : 'Super Admin'
            ]);

            $query = AccountancyChartOfAccount::query();

            // Filter by company_id if not superadmin
            if ($companyId) {
                $query->getByCompanyId($companyId);
            }

            $query->with('parent');

            // Debug: check query count
            $count = $query->count();
            \Log::info('Query count', ['count' => $count]);

            $datatable = DataTables::of($query)
                ->addColumn('code_formatted', function (ChartOfAccount $account) {
                    return '<span class="badge badge-info">' . e($account->code) . '</span>';
                })
                ->addColumn('name_formatted', function (ChartOfAccount $account) {
                    $indent = '';
                    if ($account->level > 1) {
                        $indent = '<span style="margin-left: ' . (($account->level - 1) * 20) . 'px;">└─</span>';
                    }
                    return $indent . e($account->name);
                })
                ->addColumn('type_formatted', function (ChartOfAccount $account) {
                    return '<span class="badge badge-' . $account->type_badge_class . '">' . ucfirst($account->type) . '</span>';
                })
                ->addColumn('category_formatted', function (ChartOfAccount $account) {
                    return $account->formatted_category;
                })
                ->addColumn('parent_formatted', function (ChartOfAccount $account) {
                    return $account->parent ? e($account->parent->full_name) : '-';
                })
                ->addColumn('status_formatted', function (ChartOfAccount $account) {
                    $badgeClass = $account->is_active ? 'success' : 'danger';
                    $status = $account->is_active ? 'Aktif' : 'Nonaktif';
                    return '<span class="badge badge-' . $badgeClass . '">' . $status . '</span>';
                })
                ->addColumn('actions', function (ChartOfAccount $account) {
                    return view('chart_of_accounts.partials.actions', compact('account'))->render();
                })
                ->rawColumns(['code_formatted', 'name_formatted', 'type_formatted', 'status_formatted', 'actions'])
                ->make(true);

            \Log::info('DataTables response created successfully');
            return $datatable;

        } catch (\Exception $e) {
            \Log::error('DataTables error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new chart of account.
     */
    public function create(Request $request): View
    {
        $companyId = $this->getCompanyId();
        $parentAccounts = AccountancyChartOfAccount::getByCompanyId($companyId)
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart_of_accounts.create', compact('parentAccounts'));
    }

    /**
     * Store a newly created chart of account.
     */
    public function store(StoreChartOfAccountRequest $request): RedirectResponse|JsonResponse
    {
        try {
            \Log::info('Store COA request received', [
                'data' => $request->all(),
                'user' => Auth::user()->email,
                'company_id' => $this->getCompanyId()
            ]);

            $validated = $request->validated();
            $validated['id'] = (string) Str::uuid();

            // Handle company_id based on user role
            $user = Auth::user();
            if ($user->hasRole('superadmin')) {
                // For superadmin, create or get a special company for global COA
                $globalCompany = \App\Models\AccountancyCompany::firstOrCreate(
                    ['name' => 'Global System'],
                    [
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'email' => 'system@global.com',
                        'name' => 'Global System'
                    ]
                );
                $validated['accountancy_company_id'] = $globalCompany->id;
            } else {
                $validated['accountancy_company_id'] = $this->getCompanyId();
            }

            $validated['is_active'] = $request->boolean('is_active', true);

            \Log::info('Validated data', $validated);

            $account = AccountancyChartOfAccount::create($validated);

            if (!$account->isRoot()) {
                $account->updatePath();
            }

            \Log::info('COA created successfully', ['id' => $account->id]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun berhasil ditambahkan.',
                    'data' => $account
                ]);
            }

            return redirect()
                ->route('chart-of-accounts.index')
                ->with('success', 'Akun berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating COA', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified chart of account.
     */
    public function show(Request $request, string $id): View
    {
        $account = $this->findAccountOrFail($request, $id);
        return view('chart_of_accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified chart of account.
     */
    public function edit(Request $request, string $id): View|JsonResponse
    {
        $companyId = $this->getCompanyId();
        $account = $this->findAccountOrFail($request, $id);

        if ($request->ajax()) {
            return response()->json($account);
        }

        $parentAccounts = AccountancyChartOfAccount::getByCompanyId($companyId)
            ->where('id', '!=', $id)
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart_of_accounts.edit', compact('account', 'parentAccounts'));
    }

    /**
     * Update the specified chart of account.
     */
    public function update(UpdateChartOfAccountRequest $request, ChartOfAccount $chart_of_account): RedirectResponse|JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active', true);

            $chart_of_account->update($validated);

            if ($chart_of_account->wasChanged('parent_id')) {
                $chart_of_account->updatePath();
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun berhasil diupdate.',
                    'data' => $chart_of_account
                ]);
            }

            return redirect()
                ->route('chart-of-accounts.index')
                ->with('success', 'Akun berhasil diupdate.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified chart of account.
     */
    public function destroy(ChartOfAccount $chart_of_account): JsonResponse
    {
        try {
            if (!$chart_of_account->isLeaf()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang memiliki sub-akun.'
                ]);
            }

            $chart_of_account->delete();

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus akun.'
            ]);
        }
    }

    /**
     * Export chart of accounts to Excel.
     */
    public function export()
    {
        return Excel::download(new ChartOfAccountsExport(), 'chart_of_accounts.xlsx');
    }

    /**
     * Find chart of account by ID and company ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function findAccountOrFail(Request $request, string $id): ChartOfAccount
    {
        $companyId = $this->getCompanyId();
        return AccountancyChartOfAccount::getByCompanyId($companyId)
            ->where('id', $id)
            ->firstOrFail();
    }
}
