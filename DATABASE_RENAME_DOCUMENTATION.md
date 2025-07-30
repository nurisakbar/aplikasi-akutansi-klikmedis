# Dokumentasi Perubahan Nama Tabel Database

## Ringkasan Perubahan

Semua tabel database telah diubah dengan prefix `akuntansi_` untuk memberikan identitas yang jelas bahwa ini adalah sistem akuntansi.

## Daftar Tabel yang Diubah

### Tabel yang Sudah Diubah:
1. `customers` → `akuntansi_customers`
2. `suppliers` → `akuntansi_suppliers`
3. `chart_of_accounts` → `akuntansi_chart_of_accounts`
4. `journal_entries` → `akuntansi_journal_entries`
5. `journal_entry_lines` → `akuntansi_journal_entry_lines`
6. `cash_bank_transactions` → `akuntansi_cash_bank_transactions`
7. `accounts_receivable` → `akuntansi_accounts_receivable`
8. `accounts_payable` → `akuntansi_accounts_payable`
9. `accounts_receivable_payments` → `akuntansi_accounts_receivable_payments`
10. `accounts_payable_payments` → `akuntansi_accounts_payable_payments`
11. `fixed_assets` → `akuntansi_fixed_assets`
12. `expenses` → `akuntansi_expenses`
13. `taxes` → `akuntansi_taxes`

### Tabel Laravel Bawaan (Tidak Diubah):
1. `users` (Laravel default)
2. `password_reset_tokens` (Laravel default)
3. `failed_jobs` (Laravel default)
4. `personal_access_tokens` (Laravel default)

## File Migration yang Dibuat

1. `2025_01_27_000001_rename_tables_with_akuntansi_prefix.php` - Rename semua tabel
2. `2025_01_27_000002_update_foreign_key_constraints.php` - Update foreign key constraints
3. `2025_01_27_000003_update_chart_of_accounts_self_reference.php` - Update self-referencing FK
4. `2025_01_27_000004_update_chart_of_accounts_unique_constraint.php` - Update unique constraint
5. `2025_01_27_000005_update_journal_entries_unique_constraint.php` - Update unique constraint

## Model yang Diupdate

Semua model telah diupdate untuk menggunakan nama tabel baru:

- `Customer` → `akuntansi_customers`
- `Supplier` → `akuntansi_suppliers`
- `ChartOfAccount` → `akuntansi_chart_of_accounts`
- `JournalEntry` → `akuntansi_journal_entries`
- `JournalEntryLine` → `akuntansi_journal_entry_lines`
- `CashBankTransaction` → `akuntansi_cash_bank_transactions`
- `AccountsReceivable` → `akuntansi_accounts_receivable`
- `AccountsPayable` → `akuntansi_accounts_payable`
- `AccountsReceivablePayment` → `akuntansi_accounts_receivable_payments`
- `AccountsPayablePayment` → `akuntansi_accounts_payable_payments`
- `FixedAsset` → `akuntansi_fixed_assets`
- `Expense` → `akuntansi_expenses`
- `Tax` → `akuntansi_taxes`

## Seeder yang Diupdate

- `ChartOfAccountSeeder` - Menggunakan nama tabel baru `akuntansi_chart_of_accounts`

## Cara Menjalankan Perubahan

1. Jalankan migration untuk rename tabel:
```bash
php artisan migrate
```

2. Jika ada error, rollback dan jalankan ulang:
```bash
php artisan migrate:rollback
php artisan migrate
```

3. Jalankan seeder jika diperlukan:
```bash
php artisan db:seed --class=ChartOfAccountSeeder
```

## Catatan Penting

- Semua foreign key constraints telah diupdate
- Unique constraints telah diupdate
- Self-referencing constraints telah diupdate
- Data existing akan tetap aman
- Backup database sebelum menjalankan migration

## Troubleshooting

Jika terjadi error saat migration:

1. Pastikan tidak ada aplikasi yang sedang mengakses database
2. Backup database terlebih dahulu
3. Rollback migration dan jalankan ulang
4. Periksa log error di `storage/logs/laravel.log` 