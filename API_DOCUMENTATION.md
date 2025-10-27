# Document Management System - CloudBOX

Hệ thống quản lý tài liệu được xây dựng bằng Laravel với cấu trúc MVC, hỗ trợ cả Web Interface và RESTful API.

## 🚀 Tính năng

### Web Features
- ✅ Đăng nhập / Đăng ký người dùng
- ✅ Dashboard với thống kê chi tiết
- ✅ Upload và quản lý files
- ✅ Tạo và quản lý folders
- ✅ Tìm kiếm files
- ✅ Đánh dấu files yêu thích
- ✅ Thùng rác (Trash) với khả năng khôi phục
- ✅ Quản lý storage quota
- ✅ Recent files
- ✅ Responsive design

### API Features
- ✅ RESTful API với authentication (Laravel Sanctum)
- ✅ CRUD operations cho Files và Folders
- ✅ Upload/Download files
- ✅ Soft delete và restore
- ✅ Filter và search
- ✅ Pagination support

## 📋 Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Laravel 10.x
- Node.js & NPM (optional, cho assets)

## 🛠️ Cài đặt

### 1. Clone project và cài đặt dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Update autoload để load helpers
composer dump-autoload
```

### 2. Cấu hình môi trường

```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Cấu hình database trong file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=documentmanagement
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Chạy migrations

```bash
# Chạy migrations để tạo database tables
php artisan migrate

# Hoặc migrate với seed data (nếu có)
php artisan migrate --seed
```

### 5. Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### 6. Khởi động server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 📁 Cấu trúc Database

### Users Table
- id, name, email, password
- storage_limit (default: 20GB)
- storage_used
- avatar

### Folders Table
- id, user_id, parent_id, name
- timestamps

### Files Table
- id, user_id, folder_id
- name, filename, path
- size, mime_type
- is_favourite
- deleted_at (soft delete)
- timestamps

## 🌐 Web Routes

### Authentication
- `GET /login` - Trang đăng nhập
- `POST /login` - Xử lý đăng nhập
- `GET /register` - Trang đăng ký
- `POST /register` - Xử lý đăng ký
- `POST /logout` - Đăng xuất

### Dashboard & Files
- `GET /dashboard` - Trang dashboard chính
- `GET /files` - Danh sách files
- `POST /files` - Upload file mới
- `GET /files/{id}` - Xem chi tiết file
- `GET /files/{id}/download` - Download file
- `DELETE /files/{id}` - Xóa file (soft delete)

### Folders
- `GET /folders` - Danh sách folders
- `POST /folders` - Tạo folder mới
- `GET /folders/{id}` - Xem folder
- `PUT /folders/{id}` - Cập nhật folder
- `DELETE /folders/{id}` - Xóa folder

### Special Pages
- `GET /recent` - Files gần đây
- `GET /favourites` - Files yêu thích
- `GET /trash` - Thùng rác
- `GET /search` - Tìm kiếm

## 🔌 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication APIs

#### Register
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}

Response:
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {...},
        "access_token": "1|xxxxx",
        "token_type": "Bearer"
    }
}
```

#### Get Current User
```http
GET /api/me
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### File APIs

#### List Files
```http
GET /api/files?folder_id=1&search=document&sort_by=created_at&sort_order=desc&per_page=20
Authorization: Bearer {token}
```

#### Upload File
```http
POST /api/files
Authorization: Bearer {token}
Content-Type: multipart/form-data

file: [binary]
folder_id: 1 (optional)
```

#### Get File Details
```http
GET /api/files/{id}
Authorization: Bearer {token}
```

#### Update File
```http
PUT /api/files/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "New Name.pdf",
    "folder_id": 2,
    "is_favourite": true
}
```

#### Delete File (Soft Delete)
```http
DELETE /api/files/{id}
Authorization: Bearer {token}
```

#### Download File
```http
GET /api/files/{id}/download
Authorization: Bearer {token}
```

#### Get Trash Files
```http
GET /api/files/trash/list
Authorization: Bearer {token}
```

#### Restore File
```http
POST /api/files/{id}/restore
Authorization: Bearer {token}
```

#### Permanently Delete File
```http
DELETE /api/files/{id}/force
Authorization: Bearer {token}
```

#### Get Favourite Files
```http
GET /api/files/favourites/list
Authorization: Bearer {token}
```

#### Get Recent Files
```http
GET /api/files/recent/list
Authorization: Bearer {token}
```

### Folder APIs

#### List Folders
```http
GET /api/folders?parent_id=null
Authorization: Bearer {token}
```

#### Create Folder
```http
POST /api/folders
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "My Documents",
    "parent_id": null
}
```

#### Get Folder Details
```http
GET /api/folders/{id}
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": {
        "folder": {...},
        "subfolders": [...],
        "files": [...]
    }
}
```

#### Update Folder
```http
PUT /api/folders/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "parent_id": 2
}
```

#### Delete Folder
```http
DELETE /api/folders/{id}
Authorization: Bearer {token}
```

#### Get Folder Tree
```http
GET /api/folders/tree/all
Authorization: Bearer {token}
```

## 📝 Helper Functions

```php
// Format bytes to human readable
formatBytes(1024); // "1 KB"

// Get file icon class
getFileIcon('application/pdf'); // "las la-file-pdf"

// Get file type category
getFileType('image/jpeg'); // "image"
```

## 🔒 Authentication

### Web Authentication
Sử dụng Laravel's built-in session-based authentication.

### API Authentication
Sử dụng Laravel Sanctum với Bearer token.

**Lưu ý:** Tất cả API requests (trừ register và login) phải include token trong header:
```
Authorization: Bearer {your_token_here}
```

## 💾 Storage

Files được lưu trong `storage/app/files/{user_id}/` với tên ngẫu nhiên để bảo mật.

Mỗi user có:
- `storage_limit`: Giới hạn dung lượng (default: 20GB)
- `storage_used`: Dung lượng đã sử dụng

## 🎨 Frontend Assets

Assets được đặt trong thư mục `public/assets/`:
- CSS: `public/assets/css/`
- JavaScript: `public/assets/js/`
- Images: `public/assets/images/`
- Vendor libraries: `public/assets/vendor/`

## 🧪 Testing

```bash
# Run tests
php artisan test
```

## 📚 Tài liệu thêm

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [RESTful API Best Practices](https://restfulapi.net/)

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 👨‍💻 Author

Developed with ❤️ using Laravel Framework

---

**Lưu ý quan trọng:**
1. Chạy `composer dump-autoload` sau khi thêm helper functions
2. Chạy migrations trước khi sử dụng
3. Đảm bảo storage folder có quyền write
4. Cấu hình CORS nếu API được gọi từ domain khác
