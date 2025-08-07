<?php

namespace App\Http\Controllers;

use App\Models\AccountancyChartOfAccount;
use App\Models\AccountancyCompany;
use App\Http\Requests\StoreChartOfAccountRequest;
use App\Http\Requests\UpdateChartOfAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use App\Exports\ChartOfAccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Enums\AccountType;
use App\Enums\AccountCategory;

class ChartOfAccountController extends Controller
{


    /**
     * Display a listing of chart of accounts.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->getDataTableResponse($request);
        }

        return view('chart_of_accounts.index');
    }

    /**
     * Get DataTables response for chart of accounts.
     */
        private function getDataTableResponse(Request $request): JsonResponse
    {
        // Ambil company_id berdasarkan user
        $user = Auth::user();
        $companyId = $user->hasRole('superadmin') 
            ? AccountancyCompany::where('name', 'Global System')->first()?->id 
            : $user->accountancy_company_id;
        
        $query = AccountancyChartOfAccount::with('parent')
            ->where('accountancy_company_id', $companyId)
            ->orderBy('path', 'asc'); // Urutkan berdasarkan path untuk hierarki yang benar

        $datatable = DataTables::of($query)
            ->addColumn('code_formatted', function (AccountancyChartOfAccount $account) {
                return '<span class="badge badge-info">' . e($account->code) . '</span>';
            })
            ->addColumn('name_formatted', function (AccountancyChartOfAccount $account) {
                $indent = '';
                if ($account->level > 1) {
                    $indent = '<span style="margin-left: ' . (($account->level - 1) * 25) . 'px;">└─</span>';
                }
                return $indent . e($account->name);
            })
            ->addColumn('type_formatted', function (AccountancyChartOfAccount $account) {
                return '<span class="badge badge-' . $account->type_badge_class . '">' . ucfirst($account->type->value) . '</span>';
            })
            ->addColumn('category_formatted', function (AccountancyChartOfAccount $account) {
                return $account->formatted_category;
            })
            ->addColumn('parent_formatted', function (AccountancyChartOfAccount $account) {
                return $account->parent ? e($account->parent->full_name) : '-';
            })
            ->addColumn('status_formatted', function (AccountancyChartOfAccount $account) {
                $badgeClass = $account->is_active ? 'success' : 'danger';
                $status = $account->is_active ? 'Aktif' : 'Nonaktif';
                return '<span class="badge badge-' . $badgeClass . '">' . $status . '</span>';
            })
            ->addColumn('actions', function (AccountancyChartOfAccount $account) {
                return view('chart_of_accounts.partials.actions', compact('account'))->render();
            })
            ->rawColumns(['code_formatted', 'name_formatted', 'type_formatted', 'status_formatted', 'actions'])
            ->make(true);

        return $datatable;
    }

    /**
     * Show the form for creating a new chart of account.
     */
    public function create()
    {
        // Ambil company_id berdasarkan user
        $user = Auth::user();
        $companyId = $user->hasRole('superadmin') 
            ? AccountancyCompany::where('name', 'Global System')->first()?->id 
            : $user->accountancy_company_id;
        
        $parentAccounts = AccountancyChartOfAccount::active()
            ->where('accountancy_company_id', $companyId)
            ->orderBy('path', 'asc') // Urutkan berdasarkan hierarki
            ->get();

        return view('chart_of_accounts.create', compact('parentAccounts'));
    }

    /**
     * Store a newly created chart of account.
     */
    public function store(StoreChartOfAccountRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['id'] = (string) Str::uuid();

            // Handle company_id based on user role
            $user = Auth::user();
            if ($user->hasRole('superadmin')) {
                // For superadmin, create or get a special company for global COA
                $globalCompany = AccountancyCompany::firstOrCreate(
                    ['name' => 'Global System'],
                    [
                        'id' => (string) Str::uuid(),
                        'email' => 'system@global.com',
                        'name' => 'Global System'
                    ]
                );
                $validated['accountancy_company_id'] = $globalCompany->id;
            } else {
                $validated['accountancy_company_id'] = $user->accountancy_company_id;
            }

            $validated['is_active'] = $request->boolean('is_active', true);

            $account = AccountancyChartOfAccount::create($validated);

            if (!$account->isRoot()) {
                $account->updatePath();
            }

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
    public function show(AccountancyChartOfAccount $chart_of_account): View
    {
        return view('chart_of_accounts.show', compact('chart_of_account'));
    }

    /**
     * Show the form for editing the specified chart of account.
     */
    public function edit(Request $request, AccountancyChartOfAccount $chart_of_account): View|JsonResponse
    {
        if ($request->ajax()) {
            return response()->json($chart_of_account);
        }

        // Ambil company_id berdasarkan user
        $user = Auth::user();
        $companyId = $user->hasRole('superadmin') 
            ? AccountancyCompany::where('name', 'Global System')->first()?->id 
            : $user->accountancy_company_id;

        $parentAccounts = AccountancyChartOfAccount::where('id', '!=', $chart_of_account->id)
            ->where('accountancy_company_id', $companyId)
            ->active()
            ->orderBy('path', 'asc') // Urutkan berdasarkan hierarki
            ->get();

        return view('chart_of_accounts.edit', compact('chart_of_account', 'parentAccounts'));
    }

    /**
     * Update the specified chart of account.
     */
    public function update(UpdateChartOfAccountRequest $request, AccountancyChartOfAccount $chart_of_account): RedirectResponse|JsonResponse
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
    public function destroy(AccountancyChartOfAccount $chart_of_account): JsonResponse
    {
        try {
            if (!$chart_of_account->isLeaf()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang memiliki sub-akun.'
                ], 422);
            }

            $chart_of_account->delete();

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus akun: ' . $e->getMessage()
            ], 500);
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
     * Fix hierarchy for current company's chart of accounts.
     */
    public function fixHierarchy(): JsonResponse
    {
        try {
            $this->ensureAllPathsAreUpdated();
            
            return response()->json([
                'success' => true,
                'message' => 'Hierarki Chart of Accounts berhasil diperbaiki.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get account types and categories for AJAX request.
     */
    public function getAccountTypesAndCategories(): JsonResponse
    {
        $types = AccountType::getOptions();
        $categories = AccountCategory::getOptions();
        
        // Group categories by type
        $categoriesByType = [
            'asset' => [
                AccountCategory::CURRENT_ASSET->value => AccountCategory::CURRENT_ASSET->getLabel(),
                AccountCategory::FIXED_ASSET->value => AccountCategory::FIXED_ASSET->getLabel(),
                AccountCategory::OTHER_ASSET->value => AccountCategory::OTHER_ASSET->getLabel(),
            ],
            'liability' => [
                AccountCategory::CURRENT_LIABILITY->value => AccountCategory::CURRENT_LIABILITY->getLabel(),
                AccountCategory::LONG_TERM_LIABILITY->value => AccountCategory::LONG_TERM_LIABILITY->getLabel(),
            ],
            'equity' => [
                AccountCategory::EQUITY->value => AccountCategory::EQUITY->getLabel(),
            ],
            'revenue' => [
                AccountCategory::OPERATING_REVENUE->value => AccountCategory::OPERATING_REVENUE->getLabel(),
                AccountCategory::OTHER_REVENUE->value => AccountCategory::OTHER_REVENUE->getLabel(),
            ],
            'expense' => [
                AccountCategory::OPERATING_EXPENSE->value => AccountCategory::OPERATING_EXPENSE->getLabel(),
                AccountCategory::OTHER_EXPENSE->value => AccountCategory::OTHER_EXPENSE->getLabel(),
            ],
        ];

        return response()->json([
            'types' => $types,
            'categories' => $categories,
            'categoriesByType' => $categoriesByType
        ]);
    }

    /**
     * Memastikan semua akun memiliki path yang benar
     */
    private function ensureAllPathsAreUpdated(): void
    {
        // Ambil company_id berdasarkan user
        $user = Auth::user();
        $companyId = $user->hasRole('superadmin') 
            ? AccountancyCompany::where('name', 'Global System')->first()?->id 
            : $user->accountancy_company_id;
        
        // Ambil semua akun root terlebih dahulu
        $rootAccounts = AccountancyChartOfAccount::whereNull('parent_id')
            ->where('accountancy_company_id', $companyId)
            ->get();
        
        foreach ($rootAccounts as $rootAccount) {
            $this->updateAccountPathRecursively($rootAccount);
        }
    }

    /**
     * Update path secara rekursif untuk akun dan semua child-nya
     */
    private function updateAccountPathRecursively(AccountancyChartOfAccount $account): void
    {
        // Update path untuk akun ini
        $account->updatePath();
        
        // Update path untuk semua child (hanya dari company yang sama)
        $children = AccountancyChartOfAccount::where('parent_id', $account->id)
            ->where('accountancy_company_id', $account->accountancy_company_id)
            ->get();
        foreach ($children as $child) {
            $this->updateAccountPathRecursively($child);
        }
    }

    /**
     * Find chart of account by ID and company ID.
     *
     * @throws ModelNotFoundException
     */
    private function findAccountOrFail(Request $request, string $id): AccountancyChartOfAccount
    {
        return AccountancyChartOfAccount::where('id', $id)->firstOrFail();
    }
}
