<?php

namespace App\Http\Controllers;

use App\Services\AccountancyCashBank\CashBankService;
use App\Http\Requests\AccountancyCashBank\StoreCashBankRequest;
use App\Http\Requests\AccountancyCashBank\UpdateCashBankRequest;
use App\Models\AccountancyCashBankTransaction;
use App\Models\AccountancyChartOfAccount;
use App\Exports\CashBankExport;
use App\Enums\CashBankTransactionType;
use App\Enums\CashBankTransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AccountancyCompany;

class CashBankController extends Controller
{
    public function __construct(
        private CashBankService $service
    ) {}

    private function getCompanyId(): string
    {
        $user = Auth::user();
        
        if ($user->hasRole('superadmin')) {
            // For superadmin, use Global System company ID
            $globalCompany = AccountancyCompany::where('name', 'Global System')->first();
            return $globalCompany ? $globalCompany->id : $user->accountancy_company_id;
        }
        
        return $user->accountancy_company_id;
    }

    private function ensureHasCompany(): void
    {
        $user = Auth::user();
        
        if (!$user->accountancy_company_id && !$user->hasRole('superadmin')) {
            abort(403, 'User tidak memiliki company yang valid.');
        }
    }

    private function authorizeCashBankTransaction(AccountancyCashBankTransaction $transaction): void
    {
        $companyId = $this->getCompanyId();
        
        if ($transaction->accountancy_company_id !== $companyId) {
            abort(403, 'Unauthorized access to cash bank transaction.');
        }
    }

    public function index(Request $request): View|JsonResponse
    {
        $this->ensureHasCompany();
        
        if ($request->ajax()) {
            return $this->getDataTableResponse($request);
        }
        
        return view('cash_bank.index');
    }

    private function getDataTableResponse(Request $request): JsonResponse
    {
        $companyId = $this->getCompanyId();
        
        $query = AccountancyCashBankTransaction::with('accountancyChartOfAccount')
            ->whereHas('accountancyChartOfAccount', function($q) use ($companyId) {
                $q->where('accountancy_company_id', $companyId);
            });
            
        if ($request->filled('account_id')) {
            $query->where('accountancy_chart_of_account_id', $request->input('account_id'));
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->input('date_to'));
        }
        
        return DataTables::of($query)
            ->addColumn('account_name', function ($transaction) {
                return $transaction->accountancyChartOfAccount->code . ' - ' . $transaction->accountancyChartOfAccount->name;
            })
            ->addColumn('type_badge', function ($transaction) {
                $badgeClass = $transaction->type_badge_class;
                $label = $transaction->formatted_type;
                return '<span class="badge badge-' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('status_badge', function ($transaction) {
                $badgeClass = $transaction->status_badge_class;
                $label = $transaction->formatted_status;
                return '<span class="badge badge-' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('amount_formatted', function ($transaction) {
                return number_format($transaction->amount, 0, ',', '.');
            })
            ->addColumn('attachment', function ($transaction) {
                if ($transaction->bukti) {
                    $url = asset('storage/cash_bank_attachments/' . $transaction->bukti);
                    $extension = pathinfo($transaction->bukti, PATHINFO_EXTENSION);
                    $icon = '';
                    
                    switch (strtolower($extension)) {
                        case 'pdf':
                            $icon = 'fas fa-file-pdf text-danger';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            $icon = 'fas fa-file-image text-success';
                            break;
                        default:
                            $icon = 'fas fa-file text-secondary';
                    }
                    
                    return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-info" title="Download Bukti">' .
                           '<i class="' . $icon . '"></i> Lihat</a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('actions', function (AccountancyCashBankTransaction $transaction) {
                return view('cash_bank.partials.actions', compact('transaction'))->render();
            })
            ->rawColumns(['actions', 'type_badge', 'status_badge', 'attachment'])
            ->make(true);
    }

    public function create(): View
    {
        $this->ensureHasCompany();
        
        $companyId = $this->getCompanyId();
        
        $accounts = AccountancyChartOfAccount::active()
            ->where('accountancy_company_id', $companyId)
            ->where('type', 'asset')
            ->where('category', 'current_asset')
            ->orderBy('code')
            ->get();
            
        $transactionTypes = CashBankTransactionType::getOptions();
        $transactionStatuses = CashBankTransactionStatus::getOptions();
            
        return view('cash_bank.create', compact('accounts', 'transactionTypes', 'transactionStatuses'));
    }

    public function store(StoreCashBankRequest $request): RedirectResponse|JsonResponse
    {
        $this->ensureHasCompany();
        
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            // Handle file upload
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $filename = \Illuminate\Support\Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('cash_bank_attachments', $filename, 'public');
                $data['bukti'] = $filename;
            }
            
            $this->service->createTransaction($data);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi kas/bank berhasil disimpan.'
                ]);
            }
            
            return redirect()->route('cash-bank.index')->with('success', 'Transaksi kas/bank berhasil disimpan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(AccountancyCashBankTransaction $cashBank): View
    {
        $this->ensureHasCompany();
        $this->authorizeCashBankTransaction($cashBank);
        
        if (!$cashBank->isDraft()) {
            abort(403, 'Hanya transaksi draft yang dapat diedit.');
        }
        
        $companyId = $this->getCompanyId();
        
        $accounts = AccountancyChartOfAccount::active()
            ->where('accountancy_company_id', $companyId)
            ->where('type', 'asset')
            ->where('category', 'current_asset')
            ->orderBy('code')
            ->get();
            
        $transactionTypes = CashBankTransactionType::getOptions();
        $transactionStatuses = CashBankTransactionStatus::getOptions();
            
        return view('cash_bank.edit', compact('cashBank', 'accounts', 'transactionTypes', 'transactionStatuses'));
    }

    public function update(UpdateCashBankRequest $request, AccountancyCashBankTransaction $cashBank): RedirectResponse|JsonResponse
    {
        $this->ensureHasCompany();
        $this->authorizeCashBankTransaction($cashBank);
        
        if (!$cashBank->isDraft()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya transaksi draft yang dapat diedit.'
                ], 403);
            }
            abort(403, 'Hanya transaksi draft yang dapat diedit.');
        }
        
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            // Handle file upload
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $filename = \Illuminate\Support\Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('cash_bank_attachments', $filename, 'public');
                $data['bukti'] = $filename;
            }
            
            $this->service->updateTransaction($cashBank, $data);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi kas/bank berhasil diperbarui.'
                ]);
            }
            
            return redirect()->route('cash-bank.index')->with('success', 'Transaksi kas/bank berhasil diperbarui.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(AccountancyCashBankTransaction $cashBank, Request $request): JsonResponse
    {
        $this->ensureHasCompany();
        $this->authorizeCashBankTransaction($cashBank);
        
        if (!$cashBank->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya transaksi draft yang dapat dihapus.'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
            
            $this->service->deleteTransaction($cashBank);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi kas/bank berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $this->ensureHasCompany();
        
        $companyId = $this->getCompanyId();
        $accountId = $request->input('account_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        return Excel::download(new CashBankExport($companyId, $accountId, $dateFrom, $dateTo), 'cash_bank_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function post(AccountancyCashBankTransaction $cashBank)
    {
        $this->ensureHasCompany();
        $this->authorizeCashBankTransaction($cashBank);
        
        try {
            DB::beginTransaction();
            
            $this->service->post($cashBank);
            
            DB::commit();
            
            return redirect()->route('cash-bank.index')->with('success', 'Transaksi kas/bank berhasil diposting.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
