# Rencana Implementasi Modul Akuntansi di Laravel

Dokumen ini merangkum rencana implementasi fitur-fitur akuntansi utama yang terinspirasi dari Zoho Books, untuk aplikasi Laravel.

## Checklist Modul Akuntansi

- [x] Chart of Accounts (Daftar Akun)
- [x] Journal Entries (Jurnal Umum)
- [x] Trial Balance (Neraca Saldo)
- [x] Balance Sheet (Neraca)
- [x] Profit and Loss Statement (Laporan Laba Rugi)
- [x] Cash/Bank Management (Manajemen Kas & Bank)
- [x] Accounts Receivable (Piutang Usaha)
- [x] Accounts Payable (Hutang Usaha)
- [x] Fixed Assets (Aset Tetap)
- [x] Tax Management (Manajemen Pajak)
- [x] Expense Management (Manajemen Beban)
- [ ] Inventory (Persediaan) _(opsional)_

## Daftar Modul Akuntansi

1. **Chart of Accounts (Daftar Akun)**
   - CRUD akun (aset, kewajiban, ekuitas, pendapatan, beban)
   - Kategori akun dan kode akun

2. **Journal Entries (Jurnal Umum)**
   - Input manual transaksi jurnal
   - Otomatisasi jurnal dari modul lain
   - Validasi keseimbangan debit dan kredit

3. **General Ledger (Buku Besar)**
   - Laporan transaksi per akun
   - Filter berdasarkan periode dan akun

4. **Trial Balance (Neraca Saldo)**
   - Laporan saldo akhir semua akun
   - Validasi keseimbangan total debit dan kredit

5. **Balance Sheet (Neraca)**
   - Laporan posisi keuangan (aset, kewajiban, ekuitas)
   - Periode tertentu

6. **Profit and Loss Statement (Laporan Laba Rugi)**
   - Laporan pendapatan dan beban
   - Perhitungan laba/rugi bersih

7. **Cash/Bank Management (Manajemen Kas & Bank)**
   - Pencatatan transaksi kas dan bank
   - Rekonsiliasi bank

8. **Accounts Receivable (Piutang Usaha)**
   - Pembuatan dan pengelolaan invoice
   - Pencatatan pembayaran dari pelanggan
   - Laporan umur piutang

9. **Accounts Payable (Hutang Usaha)**
   - Pencatatan tagihan/bills dari vendor
   - Pencatatan pembayaran ke vendor
   - Laporan umur hutang

10. **Fixed Assets (Aset Tetap)**
    - Pencatatan aset tetap
    - Perhitungan dan jurnal penyusutan
    - Laporan aset

11. **Tax Management (Manajemen Pajak)**
    - Pengaturan jenis pajak (PPN, PPh, dll)
    - Pencatatan pajak keluaran dan masukan
    - Laporan pajak

12. **Expense Management (Manajemen Beban)**
    - Pencatatan pengeluaran operasional
    - Kategori beban
    - Lampiran bukti pengeluaran

13. **Inventory (Persediaan)** _(opsional)_
    - Pencatatan barang masuk/keluar
    - Penyesuaian stok
    - Laporan nilai persediaan

## Fitur Pendukung
- Rekonsiliasi bank
- Multi-currency (opsional)
- Reporting & Analytics
- Hak akses pengguna (role-based)

## Rencana Implementasi
1. **Analisis kebutuhan dan desain database**
2. **Pembuatan model dan migrasi database untuk setiap modul**
3. **Pembuatan controller, service, dan repository**
4. **Pembuatan halaman CRUD dan laporan (Blade/Livewire/Vue)**
5. **Integrasi antar modul (otomatisasi jurnal, dsb.)**
6. **Testing dan validasi**
7. **Dokumentasi penggunaan**

## Catatan
- Setiap modul akan dibuat terpisah dan dapat diintegrasikan secara bertahap.
- Standar akuntansi yang digunakan dapat disesuaikan dengan kebutuhan bisnis.
- Fitur dapat dikembangkan lebih lanjut sesuai kebutuhan pengguna.

## Standar Coding Project

Agar pengembangan konsisten dan maintainable, project ini menggunakan standar berikut:

### 1. Form Request Validation
- Semua validasi input dilakukan di Form Request (misal: `StoreChartOfAccountRequest`, `StoreJournalEntryRequest`).
- Controller hanya menerima data yang sudah tervalidasi.

### 2. Repository Pattern
- Setiap model utama memiliki Repository dan Interface (misal: `ChartOfAccountRepository`, `JournalEntryRepository`).
- Controller dan Service menggunakan dependency injection ke interface, bukan langsung ke model.
- Binding repository dilakukan di `app/Providers/RepositoryServiceProvider.php`.

### 3. Service Layer
- Business logic (misal: transaksi database, pengecekan balance, update relasi) ditempatkan di Service (misal: `ChartOfAccountService`, `JournalEntryService`).
- Controller hanya memanggil Service, tidak menulis business logic langsung.

### 4. Controller
- Controller hanya menangani request/response, validasi, dan pemanggilan Service/Repository.
- Tidak ada query atau business logic langsung di controller.

### 5. Resource/Collection (Opsional)
- Untuk API/JSON response gunakan Resource agar response konsisten.

### 6. Struktur Folder
- `app/Http/Requests` : Form Request
- `app/Repositories` : Repository & Interface
- `app/Services` : Service Layer
- `app/Http/Controllers` : Controller
- `app/Models` : Model Eloquent

### 7. Lain-lain
- Semua proses create/update yang kompleks harus dalam DB Transaction.
- Validasi bisnis (misal: jurnal harus balance) dilakukan di Service.
- Untuk penghapusan, response AJAX harus JSON (success/message).

Dengan standar ini, project mudah di-maintain, scalable, dan siap untuk pengembangan tim.

## Contoh Coding Standar

### 1. Form Request (Validasi)
```php
// app/Http/Requests/StoreJournalEntryRequest.php
class StoreJournalEntryRequest extends FormRequest {
    public function rules(): array {
        return [
            'date' => 'required|date',
            'lines' => 'required|array|min:2',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ];
    }
}
```

### 2. Repository & Interface
```php
// app/Repositories/Interfaces/JournalEntryRepositoryInterface.php
interface JournalEntryRepositoryInterface {
    public function all(): Collection;
    public function find(string $id): ?JournalEntry;
    public function create(array $data): JournalEntry;
}

// app/Repositories/JournalEntryRepository.php
class JournalEntryRepository implements JournalEntryRepositoryInterface {
    public function all(): Collection {
        return JournalEntry::all();
    }
    public function create(array $data): JournalEntry {
        return JournalEntry::create($data);
    }
}
```

### 3. Service Layer
```php
// app/Services/JournalEntryService.php
class JournalEntryService {
    protected $repository;
    public function __construct(JournalEntryRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    public function create(array $data): JournalEntry {
        // Validasi bisnis: jurnal harus balance
        $totalDebit = collect($data['lines'])->sum('debit');
        $totalCredit = collect($data['lines'])->sum('credit');
        if ($totalDebit != $totalCredit) {
            throw new \Exception('Total debit dan kredit harus seimbang.');
        }
        return DB::transaction(function () use ($data) {
            $entry = $this->repository->create($data);
            // ... create lines ...
            return $entry;
        });
    }
}
```

### 4. Controller
```php
// app/Http/Controllers/JournalEntryController.php
class JournalEntryController extends Controller {
    protected $service;
    public function __construct(JournalEntryService $service) {
        $this->service = $service;
    }
    public function store(StoreJournalEntryRequest $request) {
        try {
            $this->service->create($request->validated());
            return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['lines' => $e->getMessage()]);
        }
    }
}
```

Dengan pola ini, kode lebih rapi, mudah di-maintain, dan scalable untuk tim.

---

**Dokumen ini akan diperbarui seiring perkembangan implementasi.**
