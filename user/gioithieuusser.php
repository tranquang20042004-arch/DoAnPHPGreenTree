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
    /* Tiêu đề lớn */
.gt-title {
  text-align: center;
  font-size: 34px;
  font-weight: 700;
  color: #2e7d32;
  margin-bottom: 8px;
}

/* Dòng mô tả dưới tiêu đề */
.gt-sub {
  text-align: center;
  font-size: 19px;
  color: #777;
  margin-bottom: 45px;
}

/* Layout 2 cột */
.gt-content {
  display: flex;
  align-items: center;
  gap: 60px;
}

/* Cột chữ bên trái */
.gt-text {
  flex: 1;
}

.gt-text h3 {
  font-size: 22px;
  font-weight: 600;
  color: #2e7d32;
  margin-bottom: 18px;
}

.gt-text p {
  font-size: 19px;
  line-height: 1.8;
  color: #555;
  margin-bottom: 18px;
}

/* Cột ảnh bên phải */
.gt-image {
  flex: 1;
}
.header {
  height: 75px;
  padding: 0 40px;
}
.gt-image img {
  width: 100%;
  height: 380px;
  object-fit: cover;
  border-radius: 14px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
  </style>
</head>
<body>
  <div class="header">
    <div class="logo">🌿 Green Tree</div>
    <div class="header-right">
      <div class="contact">📞 0345 530 628</div>
      <div class="nav_login"><a href="../index.php">👤 Đăng kí / Đăng nhập</a></div>
    </div>
  </div>

  <div class="nav">
    <div class="nav-left">
      <a href="user.php">🏠 Trang chủ</a>
      <a href="gioithieuusser.php" class="active">ⓘ Giới thiệu</a>
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
          <div class="gioithieu">
        <h2 class="gt-title">Về Chúng Tôi</h2>
    <p class="gt-sub">
      Mang thiên nhiên đến gần bạn hơn với những loại cây cảnh cao cấp.
    </p>

        <div class="gt-content">
              <div class="gt-text">
                <h3>Chúng Tôi Là Ai</h3>
                <p>
                  Tại Green Home, chúng tôi đam mê kết nối con người với vẻ đẹp của thiên nhiên.
                  Niềm tin của chúng tôi là cung cấp những loại cây chất lượng cao
                  và giải pháp làm vườn giúp biến không gian sống của bạn thành những
                  ốc đảo xanh tươi.
                </p>
                <p>
                  Dù bạn là người yêu thiên nhiên hay mới bắt đầu,
                  chúng tôi luôn sẵn sàng đồng hành cùng bạn trên từng bước đường.
                  Từ cây trồng trong nhà đến cảnh quan ngoài trời,
                  chúng tôi có mọi thứ bạn cần để tạo nên khu vườn mơ ước.
                </p>
              </div>

          <div class="gt-image">
        <img src="https://newstore24h.com/wp-content/uploads/2024/12/thiet-ke-cua-hang-cay-canh-42.jpg" alt="Green Tree">
      </div>
    </div>
  </div>
</body>
</html>
