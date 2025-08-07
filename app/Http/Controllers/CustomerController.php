<?php

namespace App\Http\Controllers;

use App\Models\AccountancyCustomer;
use App\Services\AccountancyCustomer\AccountancyCustomerService;
use App\Http\Requests\AccountancyCustomer\StoreCustomerRequest;
use App\Http\Requests\AccountancyCustomer\UpdateCustomerRequest;
use App\Enums\CustomerStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct(
        private AccountancyCustomerService $service
    ) {}

    private function getCompanyId(): string
    {
        $user = auth()->user();
        if ($user->hasRole('superadmin')) {
            return \App\Models\AccountancyCompany::where('name', 'Global System')->first()->id;
        }
        return $user->accountancy_company_id;
    }

    private function ensureHasCompany(): void
    {
        $user = auth()->user();
        if (!$user->hasRole('superadmin') && !$user->accountancy_company_id) {
            abort(403, 'User tidak memiliki akses ke perusahaan.');
        }
    }

    private function authorizeCustomer(AccountancyCustomer $customer): void
    {
        $user = auth()->user();
        if (!$user->hasRole('superadmin') && $customer->accountancy_company_id !== $user->accountancy_company_id) {
            abort(403, 'Anda tidak memiliki akses ke customer ini.');
        }
    }

    public function index(Request $request): View|JsonResponse
    {
        $this->ensureHasCompany();
        
        if ($request->ajax()) {
            return $this->getDataTableResponse($request);
        }
        
        return view('customers.index');
    }

    private function getDataTableResponse(Request $request): JsonResponse
    {
        $companyId = $this->getCompanyId();
        
        $query = AccountancyCustomer::where('accountancy_company_id', $companyId);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($customer) {
                $badgeClass = $customer->status_badge_class;
                $label = $customer->formatted_status;
                return '<span class="badge badge-' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('credit_limit_formatted', function ($customer) {
                return number_format($customer->credit_limit, 0, ',', '.');
            })
            ->addColumn('outstanding_balance_formatted', function ($customer) {
                return number_format($customer->outstanding_balance, 0, ',', '.');
            })
            ->addColumn('available_credit_formatted', function ($customer) {
                return number_format($customer->available_credit, 0, ',', '.');
            })
            ->addColumn('actions', function (AccountancyCustomer $customer) {
                return view('customers.partials.actions', compact('customer'))->render();
            })
            ->rawColumns(['actions', 'status_badge'])
            ->make(true);
    }

    public function create(): View
    {
        $this->ensureHasCompany();
        
        $customerStatuses = CustomerStatus::getOptions();
        
        return view('customers.create', compact('customerStatuses'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse|JsonResponse
    {
        $this->ensureHasCompany();
        
        try {
            $this->service->createCustomer($request->validated());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil ditambahkan.'
                ]);
            }
            
            return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(AccountancyCustomer $customer): View
    {
        $this->ensureHasCompany();
        $this->authorizeCustomer($customer);
        
        $customerSummary = $this->service->getCustomerSummary($customer);
        
        return view('customers.show', compact('customer', 'customerSummary'));
    }

    public function edit(AccountancyCustomer $customer): View
    {
        $this->ensureHasCompany();
        $this->authorizeCustomer($customer);
        
        $customerStatuses = CustomerStatus::getOptions();
        
        return view('customers.edit', compact('customer', 'customerStatuses'));
    }

    public function update(UpdateCustomerRequest $request, AccountancyCustomer $customer): RedirectResponse|JsonResponse
    {
        $this->ensureHasCompany();
        $this->authorizeCustomer($customer);
        
        try {
            $this->service->updateCustomer($customer, $request->validated());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil diperbarui.'
                ]);
            }
            
            return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(AccountancyCustomer $customer, Request $request): JsonResponse
    {
        $this->ensureHasCompany();
        $this->authorizeCustomer($customer);
        
        try {
            $this->service->deleteCustomer($customer);
            
            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $this->ensureHasCompany();
        
        $companyId = $this->getCompanyId();
        $customers = $this->service->getCustomersByCompany($companyId);
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CustomersExport($customers),
            'customers-' . date('Y-m-d') . '.xlsx'
        );
    }
}
