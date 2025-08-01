# Aplikasi Akuntansi KlikMedis

Aplikasi sistem akuntansi berbasis Laravel dengan fitur multi-tenant dan role-based access control.

## Fitur Utama

### 1. User & Company Registration
- **Registrasi Otomatis**: Saat user mendaftar, otomatis membuat 1 company
- **UUID Support**: User dan company menggunakan UUID sebagai primary key
- **Role Assignment**: User otomatis diberikan role `company-admin` menggunakan Spatie Laravel Permission
- **Form Fields**: name, email, password, company_name
- **Relasi**: User dan company terhubung via `users.company_id`

### 2. Login dengan Role-based Access
- **Spatie Laravel Permission**: Menggunakan package untuk manajemen role dan permission
- **Roles Available**:
  - `superadmin`: Bisa melihat semua data dari semua company
  - `company-admin`: Hanya bisa melihat data milik `company_id`-nya
- **Middleware**: `CheckRole` untuk validasi role
- **Company Validation**: `EnsureUserHasCompany` untuk memastikan user memiliki company (kecuali superadmin)

### 3. CRUD Chart of Accounts (COA)
- **Tabel**: `accountancy_chart_of_accounts`
- **Fields**: id, company_id, code, name, type, category, parent_id, description, is_active, level, path
- **AJAX Support**: Create/edit/delete menggunakan jQuery AJAX
- **AdminLTE Template**: Interface menggunakan AdminLTE
- **DataTables**: Tampilan data menggunakan DataTables
- **Modal Forms**: Form tambah/edit menggunakan Bootstrap modal
- **Company Filtering**: Query dibatasi ke `company_id = auth()->user()->company_id`

## Struktur Database

### Tabel Utama
- `companies`: Data perusahaan
- `users`: Data user dengan relasi ke company
- `accountancy_chart_of_accounts`: Chart of accounts dengan hierarki
- `roles`, `permissions`, `model_has_roles`, `model_has_permissions`: Tabel Spatie Permission

### Relasi
- User belongs to Company
- Company has many Users
- Company has many ChartOfAccounts
- ChartOfAccount belongs to Company
- ChartOfAccount belongs to Parent (self-referencing)

## Instalasi

### Prerequisites
- PHP 8.1+
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Setup
1. Clone repository
```bash
git clone <repository-url>
cd aplikasi-akutansi-klikmedis
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=akuntansi_klikmedis
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations dan seeders
```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=SuperAdminSeeder
```

6. Build assets
```bash
npm run build
```

7. Start server
```bash
php artisan serve
```

## Default Users

### Super Admin
- Email: `superadmin@klikmedis.com`
- Password: `password123`
- Role: `superadmin`
- Company: `null` (bisa akses semua data)

### Company Admin (untuk setiap company)
- Email: `admin@[companyname].com`
- Password: `password123`
- Role: `company-admin`
- Company: Sesuai dengan company yang dibuat

## API Endpoints

### Authentication
- `GET /auth/login` - Login form
- `POST /auth/login` - Login process
- `GET /auth/register` - Register form
- `POST /auth/register` - Register process
- `POST /auth/logout` - Logout

### Chart of Accounts
- `GET /chart-of-accounts` - Index (DataTables)
- `POST /chart-of-accounts` - Store (AJAX)
- `GET /chart-of-accounts/{id}/edit` - Edit form (AJAX)
- `PUT /chart-of-accounts/{id}` - Update (AJAX)
- `DELETE /chart-of-accounts/{id}` - Delete (AJAX)
- `GET /chart-of-accounts/export` - Export Excel

## Middleware

### CheckRole
Validasi role user untuk akses tertentu
```php
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Routes untuk superadmin
});
```

### EnsureUserHasCompany
Memastikan user memiliki company (kecuali superadmin)
```php
Route::middleware(['auth', 'has.company'])->group(function () {
    // Routes yang memerlukan company
});
```

## Validation Rules

### Chart of Accounts
- `code`: Required, unique per company, max 20 chars
- `name`: Required, max 100 chars
- `type`: Required, enum (asset, liability, equity, revenue, expense)
- `category`: Required, enum sesuai type
- `parent_id`: Optional, UUID, exists in same company
- `description`: Optional, text
- `is_active`: Boolean

## Frontend Features

### AJAX Implementation
- Modal forms untuk create/edit
- SweetAlert untuk konfirmasi dan notifikasi
- DataTables untuk tampilan data
- Dynamic category dropdown berdasarkan type
- Parent account dropdown

### JavaScript Functions
- `loadParentAccounts()`: Load parent accounts untuk dropdown
- `updateCategories()`: Update kategori berdasarkan tipe akun
- `loadAccountData()`: Load data akun untuk edit
- `saveAccount()`: Save/update akun via AJAX
- `deleteAccountAjax()`: Delete akun via AJAX

## Security Features

### Role-based Access Control
- Superadmin: Akses penuh ke semua data
- Company-admin: Akses terbatas ke data company-nya
- Permission-based: Granular permissions untuk setiap action

### Data Isolation
- Company-based filtering untuk semua queries
- UUID untuk primary keys
- Soft deletes untuk data preservation

### Validation
- Server-side validation dengan custom messages
- Client-side validation dengan Bootstrap
- CSRF protection untuk semua forms

## Troubleshooting

### Common Issues
1. **Permission denied**: Pastikan user memiliki role yang sesuai
2. **Company not found**: Pastikan user terhubung dengan company
3. **UUID errors**: Pastikan migration berjalan dengan benar
4. **AJAX errors**: Check browser console dan Laravel logs

### Logs
- Laravel logs: `storage/logs/laravel.log`
- DataTables logs: Check browser console
- AJAX errors: Check network tab di browser

## Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## License

This project is licensed under the MIT License.
