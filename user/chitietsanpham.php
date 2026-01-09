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

require "../config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT sp.id, sp.ten, sp.gia, sp.mo_ta, sp.so_luong, ha.url AS anh
        FROM sanpham sp
        LEFT JOIN hinhanh ha ON ha.sanpham_id = sp.id
        WHERE sp.id = $id
        LIMIT 1";// Lấy thông tin sản phẩm cùng ảnh đại diện
$result = $conn->query($sql); // Sử dụng đối tượng mysqli để truy vấn
$product = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;// Lấy mảng kết quả nếu có
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
    .header {
  height: 75px;
  padding: 0 40px;
}
    .gioithieu p {
      line-height: 1.6;
      color: #555;
    }
    body { font-family: 'Segoe UI', sans-serif; background:#f4f6f5; padding:40px; }
    .product-detail { max-width: 900px; margin:auto; background:#fff; padding:30px;
        border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); display:flex; gap:30px; }
    .product-detail img { width:350px; height:auto; object-fit:cover; border-radius:8px; }
    .info { flex:1; }
    .info h1 { color:#2e7d32; margin-bottom:10px; }
    .info h2 { margin:0; font-size:22px; color:#333; }
    .price { font-size:20px; font-weight:bold; color:#e53935; margin:15px 0; }
    .quantity-control { display:flex; align-items:center; gap:10px; margin:15px 0; }
    .quantity-control button { width:30px; height:30px; border:none; background:#2e7d32; color:#fff;
        font-size:18px; border-radius:4px; cursor:pointer; }
    .quantity-control input { width:50px; text-align:center; font-size:16px; padding:5px; }
    .add-cart { background:#e53935; color:#fff; padding:10px 20px; border:none;
        border-radius:6px; cursor:pointer; font-weight:600; }
  </style>
  <script>
function increaseQty() {
    let qty = document.getElementById("qty");
    qty.value = parseInt(qty.value) + 1;
}
function decreaseQty() {
    let qty = document.getElementById("qty");
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}
</script>
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
      <a href="gioithieuusser.php">ⓘ Giới thiệu</a>
      <a href="sanphamuser.php" class="active">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="#">🧾 Đơn mua</a>
      <a href="giohangview.php">🛒 Giỏ hàng</a>
      <a href="logout.php" style="color: #dc3545;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">🚪 Đăng xuất</a>
    </div>
  </div>

  <div class="than_body">
 

    <div class="gioithieu">
      <div class="product-detail">
    <?php if ($product): ?>
        <!-- Ảnh bên trái -->
        <img src="<?= $product['anh'] ?? 'no-image.png' ?>" alt="<?= $product['ten'] ?>">
            
        <!-- Thông tin bên phải -->
        <div class="info">
            <h1>Chi tiết sản phẩm</h1>
            <h2><?= htmlspecialchars($product['ten']) ?></h2>
            <p class="price">Giá: <?= number_format($product['gia']) ?> VNĐ</p>
            <p><?= nl2br(htmlspecialchars($product['mo_ta'])) ?></p>

            <!-- Phần tăng giảm số lượng -->
            <form action="giohangadd.php" method="POST">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <div class="quantity-control">
        <button type="button" onclick="decreaseQty()">-</button>
        <input type="text" id="qty" name="qty" value="1" readonly>
        <button type="button" onclick="increaseQty()">+</button>
    </div>
    <button type="submit" class="add-cart"> Thêm vào giỏ hàng</button>
</form>

        </div>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm.</p>
    <?php endif; ?>
    </div>
  </div>
</body>
</html>
