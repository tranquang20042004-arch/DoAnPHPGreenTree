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

    /* HEADER */
    .header {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .logo {
      font-size: 22px;
      font-weight: bold;
      color: #2e7d32;
    }

    .search-bar {
      display: flex;
      align-items: center;
      flex-grow: 1;
      margin: 0 40px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 25px;
      padding: 6px 15px;
    }

    .search-bar select, .search-bar input {
      border: none;
      outline: none;
      font-size: 15px;
      background: transparent;
    }

    .search-bar input {
      flex-grow: 1;
      padding: 6px;
    }

    /* NAV */
    .nav {
      position: fixed;
      top: 70px;
      left: 0; right: 0;
      z-index: 999;
      display: flex;
      justify-content: space-between;
      background: #ffffff;
      padding: 12px 40px;
      border-top: 1px solid #eee;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .nav-left a, .nav-right a {
      margin: 0 15px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: 0.2s;
    }

    .nav-left a:hover, .nav-right a:hover {
      color: #2e7d32;
      border-bottom: 2px solid #2e7d32;
    }

    /* BODY */
    .than_body {
      margin-top: 150px;
      padding: 40px;
      min-height: 100vh;
    }

    .gioithieu {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      max-width: 1100px;
      margin: auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .gioithieu h2 {
      color: #2e7d32;
      margin-bottom: 25px;
      text-align: center;
    }

    /* Bảng giới thiệu (đã sửa đẹp) */
    .info-table {
      width: 100%;
      border-collapse: collapse;
    }

    .info-table .title {
      font-size: 24px;
      font-weight: bold;
      padding: 10px 0;
      color: #2e7d32;
    }

    .info-table .sub {
      font-size: 16px;
      color: #555;
      padding-bottom: 20px;
    }

    .info-table .content {
      width: 55%;
      vertical-align: top;
      padding-right: 20px;
    }

    .info-table .image-cell {
      width: 45%;
      text-align: right;
    }

    .info-table img {
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      object-fit: cover;
    }
     .nav_login a {
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
    }
     .contact {
      font-size: 14px;
      color: #444;
      font-weight: 500;
     
    }
  </style>
</head>
<body>

  <!-- HEADER -->
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

  <!-- NAV -->
  <div class="nav">
    <div class="nav-left">
      <a href="user.php">🏠 Trang chủ</a>
      <a href="../user/gioithieuusser.php">ⓘ Giới thiệu</a>
      <a href="sanphamuser.php">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="#">🧾 Đơn mua</a>
      <a href="giohangview.php">🛒 Giỏ hàng</a>
      <a href="logout.php" style="color: #dc3545;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">🚪 Đăng xuất</a>
    </div>
  </div>

  <!-- BODY -->
  <div class="than_body">

    <div class="gioithieu">
      

      <table class="info-table">
        <tr >
          <td colspan="2" class="title" style="font-size: 50px;text-align: center;">Về Chúng tôi</td>
        </tr>

        <tr>
          <td colspan="2" class="sub" style="font-size: 30px;text-align: center;">Mang thiên nhiên đến gần bạn hơn với những cây cảnh cao cấp</td>
        </tr>

        <tr>
          <td class="content">
            <h3 style="font-size: 40px;">Chúng tôi là ai</h3>
            <p style="font-size: 20px;">
              Tại GreenTree, chúng tôi đam mê kết nối con người với vẻ đẹp của thiên nhiên.
              Sứ mệnh của chúng tôi là cung cấp những loại cây chất lượng cao, giúp không gian
              sống và làm việc trở nên tươi mát và sinh động hơn.
            </p>
          </td>

          <td class="image-cell">
            <img src="https://kenh14cdn.com/203336854389633024/2022/6/16/2868424664382063210708263739750521116095392n-16553750938591018387573.jpg">
          </td>
        </tr>
      </table>

    </div>

  </div>

</body>
</html>
