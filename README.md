<h2 align="center">
    <a href="https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin">
    🎓 Faculty of Information Technology (DaiNam University)
    </a>
</h2>
<h2 align="center">
    University Admission Management
</h2>
<div align="center">
    <p align="center">
        <img src="logo/aiotlab_logo.png" alt="AIoTLab Logo" width="170"/>
        <img src="logo/fitdnu_logo.png" alt="AIoTLab Logo" width="180"/>
        <img src="logo/dnu_logo.png" alt="DaiNam University Logo" width="200"/>
    </p>

[![AIoTLab](https://img.shields.io/badge/AIoTLab-green?style=for-the-badge)](https://www.facebook.com/DNUAIoTLab)
[![Faculty of Information Technology](https://img.shields.io/badge/Faculty%20of%20Information%20Technology-blue?style=for-the-badge)](https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin)
[![DaiNam University](https://img.shields.io/badge/DaiNam%20University-orange?style=for-the-badge)](https://dainam.edu.vn)
</div>


## 📖 1. Giới thiệu:
#Hệ thống Quản lý Tuyển sinh Hệ Đại học được xây dựng nhằm hỗ trợ toàn bộ quy trình tiếp nhận, xử lý hồ sơ, và quản lý kết quả xét tuyển của thí sinh.
Thay vì dựa vào các phương pháp thủ công, các biểu mẫu giấy tờ phức tạp, hay hệ thống dữ liệu phân tán dễ xảy ra sai sót, ứng dụng web này mang đến một giải pháp số hóa, tập trung, hiệu quả và minh bạch. 🚀 Nó giúp tự động hóa nhiều tác vụ lặp đi lặp lại, từ đó giảm thiểu đáng kể khối lượng công việc cho đội ngũ cán bộ tuyển sinh, đồng thời cung cấp trải nghiệm đăng ký thuận tiện cho thí sinh.

## 🔧 2. Các công nghệ được sử dụng
<div align="center">

### Hệ điều hành
![macOS](https://img.shields.io/badge/macOS-000000?style=for-the-badge&logo=macos&logoColor=F0F0F0)
[![Windows](https://img.shields.io/badge/Windows-0078D6?style=for-the-badge&logo=windows&logoColor=white)](https://www.microsoft.com/en-us/windows/)
[![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)](https://ubuntu.com/)

### Công nghệ chính
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](#)
[![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)](#)
[![SCSS](https://img.shields.io/badge/SCSS-CC6699?style=for-the-badge&logo=sass&logoColor=white)](#)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](#)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

### Web Server & Database
[![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/) 
[![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/)

### Database Management Tools
[![MySQL Workbench](https://img.shields.io/badge/MySQL_Workbench-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://dev.mysql.com/downloads/workbench/)
</div>

## 🚀 3. Hình ảnh các chức năng
### Trang đăng nhập
<img width="700" height="700" alt="image" src="images/Login.png"/>


### Trang đăng kí
<img width="700" height="1400" alt="image" src="images/Register.png"/>


### Trang chủ
<img width="700" height="700" alt="image" src="images/Trang chủ.png"/>



## ⚙️ 4. Cài đặt

### 4.1. Cài đặt công cụ, môi trường và các thư viện cần thiết

- Tải và cài đặt **XAMPP**  
  👉 https://www.apachefriends.org/download.html  
  (Khuyến nghị bản XAMPP với PHP 8.x)

- Cài đặt **Visual Studio Code** và các extension:
  - PHP Intelephense  
  - MySQL  
  - Prettier – Code Formatter  
### 4.2. Tải project
Clone project về thư mục `htdocs` của XAMPP (ví dụ ổ C):

```bash
cd F:\xampp\htdocs
https://github.com/VuVietLong1207/Quan_li_tuyen_sinh_he_dai_hoc.git
Truy cập project qua đường dẫn:
👉 http://localhost/admission_management/
```


### 4.3. Setup database
Mở XAMPP Control Panel, Start Apache và MySQL

Truy cập MySQL WorkBench
Tạo database:
```bash
CREATE DATABASE IF NOT EXISTS admission_managerment
   CHARACTER SET utf8mb4
   COLLATE utf8mb4_unicode_ci;
```

### 4.4. Setup tham số kết nối
Mở file config.php (hoặc .env) trong project, chỉnh thông tin DB:
```bash
<?php
class Database {
    private $host = "localhost";
    private $db_name = "admission_management";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
```

### 4.5. Chạy hệ thống
Mở XAMPP Control Panel → Start Apache và MySQL

Truy cập hệ thống:
👉 http://localhost/index.php


### 4.6. Đăng nhập lần đầu
Hệ thống cấp tài khoản cho Admin

Sau khi đăng nhập Admin có thể:

Thêm hồ sơ sinh viên

...
