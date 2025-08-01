<?php

namespace App\Http\Controllers;

use App\Models\AccountancyJournalEntry;
use App\Models\AccountancyJournalEntryLine;
use App\Models\AccountancyChartOfAccount;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreJournalEntryRequest;
use App\Http\Requests\UpdateJournalEntryRequest;
use App\Repositories\Interfaces\AccountancyJournalEntryRepositoryInterface;
use App\Services\JournalEntryService;
use App\Exports\JournalEntriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class JournalEntryController extends Controller
{
    protected $service;
    protected $repository;

    public function __construct(JournalEntryService $service, AccountancyJournalEntryRepositoryInterface $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(BaseRequest $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->getDataTableResponse($request);
        }
        return view('journal_entries.index');
    }

    private function getDataTableResponse(BaseRequest $request): JsonResponse
    {
        $query = AccountancyJournalEntry::withCount('accountancyJournalEntryLines');
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->input('date_to'));
        }
        return DataTables::of($query)
            ->addColumn('status_badge', function ($entry) {
                $badge = $entry->status === 'posted' ? 'success' : 'secondary';
                $label = $entry->status === 'posted' ? 'Posted' : 'Draft';
                return '<span class="badge badge-' . $badge . '">' . $label . '</span>';
            })
            ->addColumn('actions', function (AccountancyJournalEntry $entry) {
                return view('journal_entries.partials.actions', compact('entry'))->render();
            })
            ->rawColumns(['actions', 'status_badge'])
            ->make(true);
    }

    public function create(): View
    {
        $accounts = AccountancyChartOfAccount::orderBy('code')->get();
        return view('journal_entries.create', compact('accounts'));
    }

    public function store(StoreJournalEntryRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment');
        }
        try {
            $this->service->create($data);
            return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['lines' => $e->getMessage()]);
        }
    }

    public function show(AccountancyJournalEntry $journalEntry): View
    {
        $journalEntry->load('accountancyJournalEntryLines.accountancyChartOfAccount');
        return view('journal_entries.show', compact('journalEntry'));
    }

    public function edit(AccountancyJournalEntry $journalEntry): View
    {
        $journalEntry->load('accountancyJournalEntryLines.accountancyChartOfAccount');
        $accounts = AccountancyChartOfAccount::orderBy('code')->get();
        return view('journal_entries.edit', compact('journalEntry', 'accounts'));
    }

    public function update(UpdateJournalEntryRequest $request, AccountancyJournalEntry $journalEntry)
    {
        $data = $request->validated();
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment');
        }
        try {
            $this->service->update($journalEntry, $data);
            return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['lines' => $e->getMessage()]);
        }
    }

    public function destroy(AccountancyJournalEntry $journalEntry, BaseRequest $request): JsonResponse
    {
        try {
            $journalEntry->delete();
            return response()->json([
                'success' => true,
                'message' => 'Jurnal berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jurnal.'
            ]);
        }
    }

    public function export(BaseRequest $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        return Excel::download(new JournalEntriesExport($dateFrom, $dateTo), 'journal_entries.xlsx');
    }

    public function post(AccountancyJournalEntry $journalEntry)
    {
        try {
            $this->service->post($journalEntry);
            return redirect()->route('journal-entries.show', $journalEntry->id)->with('success', 'Jurnal berhasil diposting.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function uploadAttachment(BaseRequest $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        $file = $request->file('file');
        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('journal_attachments', $filename, 'public');
        return response()->json(['filename' => $filename]);
    }
}
