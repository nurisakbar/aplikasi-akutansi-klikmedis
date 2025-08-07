<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountancyJournalEntry\StoreJournalEntryRequest;
use App\Http\Requests\AccountancyJournalEntry\UpdateJournalEntryRequest;
use App\Services\AccountancyJournalEntry\JournalEntryService;
use App\Repositories\AccountancyJournalEntry\AccountancyJournalEntryRepositoryInterface;
use App\Models\AccountancyJournalEntry;
use App\Models\AccountancyChartOfAccount;
use App\Exports\JournalEntriesExport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\JournalEntryStatus;
use App\Models\AccountancyCompany;

class JournalEntryController extends Controller
{
    public function __construct(
        private JournalEntryService $service, 
        private AccountancyJournalEntryRepositoryInterface $repository
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

    private function authorizeJournalEntry(AccountancyJournalEntry $journalEntry): void
    {
        $companyId = $this->getCompanyId();
        
        if ($journalEntry->accountancy_company_id !== $companyId) {
            abort(403, 'Unauthorized access to journal entry.');
        }
    }

    public function index(Request $request): View|JsonResponse
    {
        $this->ensureHasCompany();
        
        if ($request->ajax()) {
            return $this->getDataTableResponse($request);
        }
        
        return view('journal_entries.index');
    }

    private function getDataTableResponse(Request $request): JsonResponse
    {
        $companyId = $this->getCompanyId();
        
        $query = AccountancyJournalEntry::withCount('accountancyJournalEntryLines')
            ->where('accountancy_company_id', $companyId);
            
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->input('date_to'));
        }
        return DataTables::of($query)
            ->addColumn('status_badge', function ($entry) {
                $badgeClass = $entry->status_badge_class;
                $label = $entry->formatted_status;
                return '<span class="badge badge-' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('lines_count', function ($entry) {
                return $entry->accountancy_journal_entry_lines_count;
            })
            ->addColumn('actions', function (AccountancyJournalEntry $entry) {
                return view('journal_entries.partials.actions', compact('entry'))->render();
            })
            ->rawColumns(['actions', 'status_badge'])
            ->make(true);
    }

    public function create(): View
    {
        $this->ensureHasCompany();
        
        $companyId = $this->getCompanyId();
        
        $accounts = AccountancyChartOfAccount::active()
            ->where('accountancy_company_id', $companyId)
            ->orderBy('code')
            ->get();
            
        return view('journal_entries.create', compact('accounts'));
    }

    public function store(StoreJournalEntryRequest $request): JsonResponse|RedirectResponse
    {
        $this->ensureHasCompany();
        
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('journal_attachments', $filename, 'public');
                $data['attachment'] = $filename;
            }
            
            $journalEntry = $this->service->create($data);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jurnal berhasil disimpan.',
                    'data' => $journalEntry
                ]);
            }
            
            return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil disimpan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()->withErrors(['lines' => $e->getMessage()]);
        }
    }

    public function show(AccountancyJournalEntry $journalEntry): View
    {
        $this->ensureHasCompany();
        
        // Check if user can access this journal entry
        $this->authorizeJournalEntry($journalEntry);
        
        $journalEntry->load('accountancyJournalEntryLines.accountancyChartOfAccount');
        return view('journal_entries.show', compact('journalEntry'));
    }

    public function edit(AccountancyJournalEntry $journalEntry): View
    {
        $this->ensureHasCompany();
        
        // Check if user can access this journal entry
        $this->authorizeJournalEntry($journalEntry);
        
        $journalEntry->load('accountancyJournalEntryLines.accountancyChartOfAccount');
        
        $companyId = $this->getCompanyId();
        
        $accounts = AccountancyChartOfAccount::active()
            ->where('accountancy_company_id', $companyId)
            ->orderBy('code')
            ->get();
            
        return view('journal_entries.edit', compact('journalEntry', 'accounts'));
    }

    public function update(UpdateJournalEntryRequest $request, AccountancyJournalEntry $journalEntry): JsonResponse|RedirectResponse
    {
        $this->ensureHasCompany();
        
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('journal_attachments', $filename, 'public');
                $data['attachment'] = $filename;
            }
            
            $this->service->update($journalEntry, $data);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jurnal berhasil diupdate.',
                    'data' => $journalEntry->fresh()
                ]);
            }
            
            return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil diupdate.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()->withErrors(['lines' => $e->getMessage()]);
        }
    }

    public function destroy(AccountancyJournalEntry $journalEntry, Request $request): JsonResponse
    {
        $this->ensureHasCompany();
        
        try {
            // Check if user can access this journal entry
            $this->authorizeJournalEntry($journalEntry);
            
            // Check if journal entry is posted
            if ($journalEntry->isPosted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jurnal yang sudah diposting tidak dapat dihapus.'
                ], 422);
            }

            // Check if journal entry has lines
            if ($journalEntry->accountancyJournalEntryLines()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jurnal yang memiliki baris jurnal tidak dapat dihapus.'
                ], 422);
            }

            $journalEntry->delete();
            return response()->json([
                'success' => true,
                'message' => 'Jurnal berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jurnal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $this->ensureHasCompany();
        
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        return Excel::download(new JournalEntriesExport($dateFrom, $dateTo), 'journal_entries.xlsx');
    }

    public function post(AccountancyJournalEntry $journalEntry)
    {
        $this->ensureHasCompany();
        
        // Check if user can access this journal entry
        $this->authorizeJournalEntry($journalEntry);
        
        try {
            $this->service->post($journalEntry);
            return redirect()->route('journal-entries.show', $journalEntry->id)->with('success', 'Jurnal berhasil diposting.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
