<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
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

class ChartOfAccountController extends Controller
{
    /**
     * Get setting ID from session.
     * TODO: Replace with actual user's setting_id when auth is implemented
     */
    protected function getSettingId(Request $request): string
    {
        $defaultSettingId = '11111111-1111-1111-1111-111111111111';

        if (!$request->session()->has('setting_id')) {
            $request->session()->put('setting_id', $defaultSettingId);
        }

        return $request->session()->get('setting_id');
    }

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
        $settingId = $this->getSettingId($request);

        $query = ChartOfAccount::getBySettingId($settingId)->with('parent');

        return DataTables::of($query)
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
    }

    /**
     * Show the form for creating a new chart of account.
     */
    public function create(Request $request): View
    {
        $settingId = $this->getSettingId($request);
        $parentAccounts = ChartOfAccount::getBySettingId($settingId)
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart_of_accounts.create', compact('parentAccounts'));
    }

    /**
     * Store a newly created chart of account.
     */
    public function store(StoreChartOfAccountRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['id'] = (string) Str::uuid();
        $validated['setting_id'] = $this->getSettingId($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        $account = ChartOfAccount::create($validated);

        if (!$account->isRoot()) {
            $account->updatePath();
        }

        return redirect()
            ->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil ditambahkan.');
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
    public function edit(Request $request, string $id): View
    {
        $settingId = $this->getSettingId($request);
        $account = $this->findAccountOrFail($request, $id);

        $parentAccounts = ChartOfAccount::getBySettingId($settingId)
            ->where('id', '!=', $id)
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart_of_accounts.edit', compact('account', 'parentAccounts'));
    }

    /**
     * Update the specified chart of account.
     */
    public function update(UpdateChartOfAccountRequest $request, ChartOfAccount $chart_of_account): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $chart_of_account->update($validated);

        if ($chart_of_account->wasChanged('parent_id')) {
            $chart_of_account->updatePath();
        }

        return redirect()
            ->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil diupdate.');
    }

    /**
     * Remove the specified chart of account.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $account = $this->findAccountOrFail($request, $id);

            if (!$account->isLeaf()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang memiliki sub-akun.'
                ]);
            }

            $account->delete();

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
     * Find chart of account by ID and setting ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function findAccountOrFail(Request $request, string $id): ChartOfAccount
    {
        $settingId = $this->getSettingId($request);
        return ChartOfAccount::getBySettingId($settingId)
            ->where('id', $id)
            ->firstOrFail();
    }
}
