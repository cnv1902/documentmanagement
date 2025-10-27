# 🚀 Hướng dẫn Setup & Chạy Project

## ✅ Đã hoàn thành

Dự án đã được chuyển đổi hoàn chỉnh sang Laravel MVC với các thành phần:

### 📂 Blade Templates
- ✅ `resources/views/layouts/auth.blade.php` - Layout cho authentication
- ✅ `resources/views/layouts/app.blade.php` - Layout chính
- ✅ `resources/views/auth/login.blade.php` - Trang đăng nhập
- ✅ `resources/views/auth/register.blade.php` - Trang đăng ký
- ✅ `resources/views/dashboard/index.blade.php` - Dashboard
- ✅ `resources/views/partials/sidebar.blade.php` - Sidebar
- ✅ `resources/views/partials/navbar.blade.php` - Navbar
- ✅ `resources/views/partials/footer.blade.php` - Footer

### 🎮 Controllers
- ✅ `app/Http/Controllers/Auth/AuthController.php` - Authentication
- ✅ `app/Http/Controllers/DashboardController.php` - Dashboard
- ✅ `app/Http/Controllers/FileController.php` - File management
- ✅ `app/Http/Controllers/FolderController.php` - Folder management

### 🔌 API Controllers
- ✅ `app/Http/Controllers/Api/AuthController.php` - API Authentication
- ✅ `app/Http/Controllers/Api/FileController.php` - API File management
- ✅ `app/Http/Controllers/Api/FolderController.php` - API Folder management

### 📊 Models
- ✅ `app/Models/User.php` - User model (với storage management)
- ✅ `app/Models/File.php` - File model
- ✅ `app/Models/Folder.php` - Folder model

### 🗄️ Migrations
- ✅ `2024_01_01_000001_add_storage_to_users_table.php`
- ✅ `2024_01_01_000002_create_folders_table.php`
- ✅ `2024_01_01_000003_create_files_table.php`

### 🛣️ Routes
- ✅ `routes/web.php` - Web routes (đầy đủ)
- ✅ `routes/api.php` - API routes (đầy đủ)

### 🔧 Helpers
- ✅ `app/Helpers/helpers.php` - Helper functions (formatBytes, getFileIcon, getFileType)

---

## 🏃 Các bước chạy project

### Bước 1: Cấu hình Database

Mở file `.env` và cấu hình database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=documentmanagement
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 2: Tạo Database

Mở MySQL và tạo database:

```sql
CREATE DATABASE documentmanagement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Hoặc dùng phpMyAdmin/HeidiSQL để tạo database.

### Bước 3: Chạy Migrations

```bash
php artisan migrate
```

Lệnh này sẽ tạo tất cả các bảng cần thiết:
- users (với storage_limit và storage_used)
- folders
- files
- và các bảng Laravel mặc định

### Bước 4: Dump Autoload

```bash
composer dump-autoload
```

### Bước 5: Create Storage Link

```bash
php artisan storage:link
```

### Bước 6: Khởi động Server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

---

## 🧪 Testing

### Test Web Interface

1. Truy cập `http://localhost:8000`
2. Sẽ redirect đến `/login`
3. Click "Sign Up" để đăng ký tài khoản mới
4. Sau khi đăng ký, sẽ tự động đăng nhập và redirect đến Dashboard

### Test API

#### Sử dụng Postman

1. Import file `postman_collection.json` vào Postman
2. Test các endpoints:

**Register:**
```http
POST http://localhost:8000/api/register
Content-Type: application/json

{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Login:**
```http
POST http://localhost:8000/api/login
Content-Type: application/json

{
    "email": "test@example.com",
    "password": "password123"
}
```

Copy `access_token` từ response và dùng cho các request tiếp theo.

**Upload File:**
```http
POST http://localhost:8000/api/files
Authorization: Bearer {your_token}
Content-Type: multipart/form-data

file: [select file]
```

---

## 📚 Tài liệu API

Xem chi tiết trong file `API_DOCUMENTATION.md`

### Các API Endpoints chính:

**Authentication:**
- POST `/api/register` - Đăng ký
- POST `/api/login` - Đăng nhập
- POST `/api/logout` - Đăng xuất
- GET `/api/me` - Lấy thông tin user

**Files:**
- GET `/api/files` - List files
- POST `/api/files` - Upload file
- GET `/api/files/{id}` - Get file detail
- PUT `/api/files/{id}` - Update file
- DELETE `/api/files/{id}` - Soft delete
- GET `/api/files/{id}/download` - Download file
- POST `/api/files/{id}/restore` - Restore file
- DELETE `/api/files/{id}/force` - Permanently delete

**Folders:**
- GET `/api/folders` - List folders
- POST `/api/folders` - Create folder
- GET `/api/folders/{id}` - Get folder with files
- PUT `/api/folders/{id}` - Update folder
- DELETE `/api/folders/{id}` - Delete folder
- GET `/api/folders/tree/all` - Get folder tree

---

## 🔧 Troubleshooting

### Lỗi: "Base table or view not found"
**Giải pháp:** Chạy lại migrations
```bash
php artisan migrate:fresh
```

### Lỗi: "Class 'App\Helpers\helpers' not found"
**Giải pháp:** Dump autoload
```bash
composer dump-autoload
```

### Lỗi: "The stream or file storage/logs/laravel.log could not be opened"
**Giải pháp:** Cấp quyền cho thư mục storage
```bash
# Windows (PowerShell as Admin)
icacls storage /grant "Users:(OI)(CI)F" /T

# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Lỗi: "SQLSTATE[HY000] [1045] Access denied"
**Giải pháp:** Kiểm tra lại thông tin database trong file `.env`

### Upload file bị lỗi
**Giải pháp:** Kiểm tra:
1. Storage link đã được tạo chưa: `php artisan storage:link`
2. Thư mục `storage/app/files` có quyền write chưa
3. Upload size limit trong `php.ini`:
   ```ini
   upload_max_filesize = 100M
   post_max_size = 100M
   ```

---

## 🎯 Các tính năng cần implement thêm

### Views chưa có (tùy chọn):
- `resources/views/files/index.blade.php` - Danh sách files
- `resources/views/files/create.blade.php` - Upload form
- `resources/views/folders/index.blade.php` - Danh sách folders
- `resources/views/folders/show.blade.php` - Chi tiết folder
- `resources/views/pages/recent.blade.php` - Recent files
- `resources/views/pages/favourites.blade.php` - Favourite files
- `resources/views/pages/trash.blade.php` - Trash files
- `resources/views/profile/index.blade.php` - User profile
- `resources/views/settings/index.blade.php` - Settings

### Tính năng nâng cao (optional):
- ⭕ File sharing với users khác
- ⭕ File permissions & access control
- ⭕ File preview (PDF, images, documents)
- ⭕ Batch operations (multiple files)
- ⭕ File versioning
- ⭕ Activity log
- ⭕ Email notifications
- ⭕ Storage analytics & charts
- ⭕ Integration với cloud storage (S3, Google Drive)

---

## 📝 Notes

1. **IDE Helper đã được cài đặt** để giúp IDE nhận diện các methods của Laravel
2. **API sử dụng Laravel Sanctum** cho authentication
3. **Soft delete** đã được implement cho files
4. **Storage quota** đã được tính toán tự động
5. **Helper functions** đã có sẵn: `formatBytes()`, `getFileIcon()`, `getFileType()`

---

## 🎉 Hoàn thành!

Project đã sẵn sàng để sử dụng. Chỉ cần:

1. ✅ Tạo database
2. ✅ Chạy migrations
3. ✅ Khởi động server
4. ✅ Đăng ký tài khoản
5. ✅ Bắt đầu upload & quản lý files!

**Happy coding! 🚀**
