<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Ngăn cache trang
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="vi">
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

    .header-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .contact {
      font-size: 14px;
      color: #444;
      font-weight: 500;
      padding-right: 15px;
      border-right: 1px solid #ddd;
    }

    .nav_login a {
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
      transition: color 0.3s;
    }

    .nav_login a:hover {
      color: #1b5e20;
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
      padding: 8px 16px;
      border-radius: 6px;
      transition: all 0.3s;
    }

    .nav-left a:hover, .nav-right a:hover {
      color: #2e7d32;
      background: #f1f8f4;
      transform: translateY(-2px);
    }

    .nav-left a.active, .nav-right a.active {
      color: #fff;
      background: #2e7d32;
      font-weight: 600;
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
    }
/* ===== TRANG CHỦ ===== */
.trangchu {
  display: flex;
  align-items: center;
  gap: 60px;
  background: #ffffff;
  padding: 70px;
  border-radius: 16px;
}

/* Cột trái */
.trangchu-left {
  flex: 1;
}

.trangchu-left h2 {
  font-size: 40px;
  font-weight: 700;
  color: #2e7d32;
  margin-bottom: 20px;
  white-space: nowrap;
}

.trangchu-left p {
  font-size: 20px;
  line-height: 1.8;
  color: #555;
  margin-bottom: 30px;
}

/* Nút */
.btn-khampha {
  display: inline-block;
  padding: 14px 32px;
  background: #2e7d32;
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  border-radius: 30px;
  transition: 0.3s;
}

.btn-khampha:hover {
  background: #1b5e20;
  transform: translateY(-2px);
}

/* Cột phải (ảnh) */
.trangchu-right {
  flex: 1;
}

.trangchu-right img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 16px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.header {
  height: 75px;
  padding: 0 40px;
}
  </style>
</head>
<body>

  <!-- HEADER -->
  <div class="header">
    <div class="logo">🌿 Green Tree</div>
    <div class="header-right">
      <div class="contact">📞 0345 530 628</div>
      <div class="nav_login"><a href="../index.php">👤 Đăng kí / Đăng nhập</a></div>
    </div>
  </div>

  <!-- NAV -->
  <div class="nav">
    <div class="nav-left">
      <a href="user.php" class="active">🏠 Trang chủ</a>
      <a href="../user/gioithieuusser.php">ⓘ Giới thiệu</a>
      <a href="sanphamuser.php">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="donhang.php">🧾 Đơn mua</a>
      <a href="giohangview.php">🛒 Giỏ hàng</a>
      <a href="logout.php" style="color: #dc3545;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">🚪 Đăng xuất</a>
    </div>
  </div>

  <!-- BODY -->
  <div class="than_body">

    <div class="gioithieu">
       <div class="gioithieu trangchu">
  <div class="trangchu-left">
    <h2>Chào mừng đến với Green Tree</h2>
    <h3 style="font-size: 25px;"> Vẻ đẹp của thiên nhiên ngay trong tầm tay bạn</h3>
    <p>
      Tìm thấy niềm vui trong cây xanh - Khám phá bộ sưu tập cây cảnh cho mọi không gian trong cuộc sống của bạn.
    </p>
    <p>
      Chúng tôi mang đến những sản phẩm cây xanh chất lượng,
      giúp không gian sống và làm việc của bạn trở nên trong lành
      và đầy sức sống.
    </p>
    <a href="sanphamuser.php" class="btn-khampha">Khám phá sản phẩm</a>
  </div>

  <div class="trangchu-right">
    <img src="https://caydeban.com.vn/image/cache/catalog/products/cay-de-ban/binh-an_0955-600x600.JPG" alt="Green Tree">
  </div>
</div>
    </div>

</body>
</html>
