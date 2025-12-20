<?php
session_start();
require_once '../config/database.php'; // dùng excuteResult()

$error = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // mã hóa giống dữ liệu cần lưu

    // Lấy đúng user theo email
    $sql = "SELECT * FROM NguoiDung WHERE email = '$email' AND mat_khau = '$password' LIMIT 1";
    $user = excuteResult($sql);// trả về mảng

    if ($user) {
        $user = $user[0];

        // Kiểm tra tài khoản có bị khóa hay không
        if ($user['trang_thai'] == 0) {
            $error = "Tài khoản của bạn đã bị khóa!";
        } else {
            // Ghi session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['ho_ten'] = $user['ten'];
            $_SESSION['role'] = ($user['vaitro_id'] == 1) ? 'admin' : 'user';

            // Phân quyền
            if ($_SESSION['role'] == 'admin') {
                header("Location: ../admin/admin.php");
            } else {
                header("Location: ../user/user.php");
            }
            exit();
        }
    } else {
        $error = "Sai email hoặc mật khẩu!";
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
    transition: all 0.3s ease; 

   }
   
   .gioithieu button:hover{
    background-color: #3898cf;
    transform: scale(1.05);
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
      <a href="../khachtruycap/trangchu.php">🏠️ Trang chủ</a>
      <a href="../khachtruycap/gioithieu.php">ⓘ Giới thiệu</a>
      <a href="../khachtruycap/sanpham.php">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="#" onclick="alert('Bạn cần đăng nhập trước.')">🧾 Đơn mua</a>
      <a href="#" onclick="alert('Bạn cần đăng nhập trước.')">🛒 Giỏ hàng</a>
    </div>
  </div>

  <div class="than_body">
   

    <div class="gioithieu" style="max-width:400px; margin:auto;">
        <h2 style="text-align: center;">👤 Đăng nhập Green Tree</h2>
        <form action="" method="POST">
            
            <div style="margin-bottom:15px;">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"
                        style="width:100%; padding:10px; border:1px solid #ccc; 
                        border-radius:5px;"
                        required>
            </div>

            <div style="margin-bottom:15px;">
                <label for="password">Mật khẩu:</label><br>
                <input type="password" id="password" name="password"
                        style="width:100%; padding:10px; border:1px solid #ccc; 
                        border-radius:5px;"
                        required>
            </div>

            <button type="submit" name="login"
                    style="background:#2e7d32; color:#fff;
                    padding:10px 20px; border:none; border-radius:5px; 
                    cursor:pointer;transition: background-color 0.3s ease;">
                Đăng nhập
            </button>
            <p style="color:red;"><?php echo $error; ?></p>
        </form>

        <p style="margin-top:15px; font-size:14px; color:#555;">
            Bạn chưa có tài khoản? <a href="newlogin.php" 
            style="color:#2e7d32; font-weight:600;">Đăng ký ngay</a>
        </p>
    </div>

  </div>
</body>
</html>
