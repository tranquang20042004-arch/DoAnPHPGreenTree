<?php
session_start();
require_once '../config/database.php';

$error = "";
$success = "";

// Xử lý khi người dùng nhấn đăng ký
if (isset($_POST['register'])) {
    $ho = mysqli_real_escape_string($conn, $_POST['ho']);
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tai_khoan = mysqli_real_escape_string($conn, $_POST['tai_khoan']);
    $matkhau1 = $_POST['mat_khau'];
    $matkhau2 = $_POST['mat_khau2'];
    $so_dien_thoai = mysqli_real_escape_string($conn, $_POST['so_dien_thoai']);

    // 1. Kiểm tra mật khẩu trùng nhau
    if ($matkhau1 !== $matkhau2) {
        $error = "Mật khẩu không trùng nhau!";
    } else {
        // 2. Kiểm tra email đã tồn tại chưa
        $checkEmail = excuteResult("SELECT * FROM NguoiDung WHERE email = '$email'");
        if ($checkEmail) {
            $error = "Email đã được sử dụng!";
        } else {
            // 3. Kiểm tra tài khoản đã tồn tại chưa
            $checkUser = excuteResult("SELECT * FROM NguoiDung WHERE tai_khoan = '$tai_khoan'");
            if ($checkUser) {
                $error = "Tên tài khoản đã tồn tại!";
            } else {
                // 4. Lưu vào database
                $passwordHash = md5($matkhau1);

                $sql = "
                    INSERT INTO NguoiDung (
                        tai_khoan, email, ho, ten, mat_khau, vaitro_id, so_dien_thoai, ngay_tao, trang_thai
                    ) VALUES (
                        '$tai_khoan', '$email', '$ho', '$ten', '$passwordHash', 2, '$so_dien_thoai', NOW(), 1
                    )
                ";

                excute($sql);
             $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Green Tree</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f5;
      color: #333;
    }

    /* Header */
    .header {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: linear-gradient(90deg, #ffffff, #f9f9f9);
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .logo {
      font-size: 22px;
      font-weight: bold;
      color: #2e7d32;
      letter-spacing: 1px;
    }

    .search-bar {
      display: flex;
      align-items: center;
      flex-grow: 1;
      margin: 0 40px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 25px;
      padding: 5px 10px;
    }

    .search-bar select, .search-bar input {
      border: none;
      outline: none;
      font-size: 15px;
      background: transparent;
    }

    .search-bar select {
      margin-right: 10px;
      color: #555;
    }

    .search-bar input {
      flex-grow: 1;
      padding: 8px;
    }

    .search-bar::after {
      content: "🔍";
      margin-left: 10px;
      color: #2e7d32;
    }

    .contact {
      font-size: 14px;
      color: #444;
      font-weight: 500;
    }

    .nav_login a {
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
    }

    /* Nav */
    .nav {
      position: fixed;
      top: 75px;
      left: 0; right: 0;
      z-index: 999;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #ffffff;
      padding: 12px 40px;
      border-top: 1px solid #eee;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .nav-left a, .nav-right a {
      margin: 0 15px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: color 0.3s, border-bottom 0.3s;
    }

    .nav-left a:hover, .nav-right a:hover {
      color: #2e7d32;
      border-bottom: 2px solid #2e7d32;
    }

    /* Body */
    .than_body {
      margin-top: 140px;
      padding: 40px;
      min-height: 100vh;
    }

    .gioithieu {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  

    }

    .gioithieu h2 {
      color: #2e7d32;
      margin-bottom: 15px;
    }

    .gioithieu p {
      line-height: 1.6;
      color: #555;
      display: flex;
  justify-content: center;
  margin-top: 20px;

   }
   .gioithieu button{
   display: block;
  margin: 20px auto; /* auto sẽ căn giữa ngang */
  background: #2e7d32;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;


   }
    
   
  </style>
</head>
<body>
  <div class="header">
    <div class="logo">🌿 Green Tree</div>
    <div class="search-bar">
      <select>
        <option>Tất cả danh mục</option>
        <option>Cây trong nhà</option>
        <option>Cây văn phòng</option>
        <option>Cây phong thủy</option>
      </select>
      <input type="text" placeholder="Tìm kiếm sản phẩm...">
    </div>
    <div class="contact">📞 0345 530 628</div>
    <div class="nav_login"><a href="#">👤 Đăng kí / Đăng nhập</a></div>
  </div>

  <div class="nav">
    <div class="nav-left">
      <a href="{{ url('/trangchu')}}">🏠️ Trang chủ</a>
      <a href="{{url('/gioithieu')}}">ⓘ Giới thiệu</a>
      <a href="{{url('/sanpham')}}">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="{{url('/donhang')}}">🧾 Đơn mua</a>
      <a href="{{url('/giohang')}}">🛒 Giỏ hàng</a>
    </div>
  </div>

  <div class="than_body">
   

    <div class="gioithieu" style="max-width:400px; margin:auto;">
        <h2 style="text-align: center;">👤 Đăng kí tài khoản mới</h2>
        <form action="" method="POST">
                <div style="margin-bottom:15px;">
                    <label>Họ:</label><br>
                    <input type="ho" id="ho" name="ho"
                            style="width:100%; padding:10px; border:1px solid #ccc; 
                            border-radius:5px;"
                            required>
                </div>

                <div style="margin-bottom:15px;">
                    <label>Tên:</label><br>
                     <input type="text" name="ten"
                            style="width:100%; padding:10px; border:1px solid #ccc; 
                            border-radius:5px;"
                            required>
                </div>
                <div style="margin-bottom:15px;">
                    <label>Tên đăng nhập:</label><br>
                    <input type="text" name="tai_khoan"
                            style="width:100%; padding:10px; border:1px solid #ccc; 
                            border-radius:5px;"
                            required>
                </div>
            <div style="margin-bottom:15px;">
                <label>Email:</label><br>
                <input type="email" name="email"
                        style="width:100%; padding:10px; border:1px solid #ccc; 
                        border-radius:5px;"
                        required>
            </div>

            <div style="margin-bottom:15px;">
                <label>Số điện thoại:</label><br>
                <input type="text" name="so_dien_thoai"
                        style="width:100%; padding:10px; border:1px solid #ccc; 
                        border-radius:5px;"
                        required>
            </div>

            <div style="margin-bottom:15px;">
                 <label>Mật khẩu:</label><br>
                  <input type="password" name="mat_khau"
                        style="width:100%; padding:10px; border:1px solid #ccc; 
                        border-radius:5px;"
                        required>
            </div>

            <div style="margin-bottom:15px;">
                <label>Xác nhận mật khẩu:</label>
                <input type="password" name="mat_khau2"
                        style="width:100%; padding:10px; border:1px solid #ccc; 
                        border-radius:5px;"
                        required>
            </div>
            <button type="submit" name="register"
                    style="background:#2e7d32; color:#fff;
                    padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                Đăng ký
            </button>
        </form>

        <p style="margin-top:15px; font-size:14px; color:#555;">
            Bạn đã có tài khoản? <a href="index.php" 
            style="color:#2e7d32; font-weight:600;">Đăng nhập ngay</a>
        </p>
        <p style="color:red;"><?= $error ?></p>
        <p style="color:green;"><?= $success ?></p>
    </div>

  </div>
</body>
</html>


<!-- <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <style>
        body { font-family: Arial; }
        form {
            width: 350px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input {
            width: 100%; margin-bottom: 10px;
            padding: 8px;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Đăng ký tài khoản</h2>

    <label>Họ:</label>
    <input type="text" name="ho" required>

    <label>Tên:</label>
    <input type="text" name="ten" required>

    <label>Tên đăng nhập:</label>
    <input type="text" name="tai_khoan" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Số điện thoại:</label>
    <input type="text" name="so_dien_thoai">

    <label>Mật khẩu:</label>
    <input type="password" name="mat_khau" required>

    <label>Nhập lại mật khẩu:</label>
    <input type="password" name="mat_khau2" required>

    <button type="submit" name="register">Đăng ký</button>

    <p style="color:red;"><?= $error ?></p>
    <p style="color:green;"><?= $success ?></p>
</form>

</body>
</html> -->
