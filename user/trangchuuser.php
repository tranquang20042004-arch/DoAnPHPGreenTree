<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

// Ngăn cache trang
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
    <div class="nav_login"><a href="../login/index.php">👤 Đăng kí / Đăng nhập</a></div>
  </div>

  <div class="nav">
    <div class="nav-left">
      <a href="trangchuuser.php">🏠️ Trang chủ</a>
      <a href="gioithieuusser.php">ⓘ Giới thiệu</a>
      <a href="sanphamuser.php">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="#">🧾 Đơn mua</a>
      <a href="giohangview.php">🛒 Giỏ hàng</a>
      <a href="logout.php" style="color: #dc3545;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">🚪 Đăng xuất</a>
    </div>
  </div>

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
    <a href="sanpham.php" class="btn-khampha">Khám phá sản phẩm</a>
  </div>

  <div class="trangchu-right">
    <img src="https://caydeban.com.vn/image/cache/catalog/products/cay-de-ban/binh-an_0955-600x600.JPG" alt="Green Tree">
  </div>
</div>
    </div>
    </div>
  </div>
</body>
</html>
`