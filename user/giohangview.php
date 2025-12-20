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
      margin-bottom: 20px;
    }

    /* Table */
    table { 
      width:100%; 
      border-collapse:collapse; 
      background:#fff; 
      border-radius:8px; 
      overflow:hidden; 
    }
    
    th, td { 
      padding:12px; 
      text-align:center; 
      border-bottom:1px solid #eee; 
    }
    
    th { 
      background:#2e7d32; 
      color:#fff; 
    }
    
    img { 
      width:80px; 
      height:80px; 
      object-fit:cover; 
      border-radius:6px; 
    }
    
    .delete-btn { 
      background:#e53935; 
      color:#fff; 
      padding:6px 12px; 
      border:none; 
      border-radius:4px; 
      cursor:pointer; 
    }

    /* Checkbox */
    .product-checkbox {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: #2e7d32;
    }
    
    /* Select all section */
    .select-all-section {
      padding: 15px 20px;
      background: #f8f9fa;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    
    .select-all-label {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
      color: #333;
      cursor: pointer;
      font-size: 16px;
    }

    /* Cart footer - Tổng tiền và nút mua */
    .cart-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 30px;
      padding: 25px 30px;
      background: #f8f9fa;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .total-section {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    
    .total-label {
      font-size: 16px;
      color: #666;
      font-weight: 500;
    }
    
    .total-amount {
      font-size: 32px;
      font-weight: bold;
      color: #2e7d32;
    }
    
    .btn-checkout {
      padding: 16px 50px;
      background: #2e7d32;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 12px rgba(46,125,50,0.2);
    }
    
    .btn-checkout:hover {
      background: #1b5e20;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(46,125,50,0.3);
    }
    
    .btn-checkout:disabled {
      background: #ccc;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .empty-cart {
      text-align: center;
      padding: 60px 20px;
      color: #999;
    }

    .empty-cart h3 {
      font-size: 24px;
      margin-bottom: 15px;
    }

    .empty-cart a {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 30px;
      background: #2e7d32;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s;
    }

    .empty-cart a:hover {
      background: #1b5e20;
      transform: translateY(-2px);
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
      <a href="user.php">🏠️ Trang chủ</a>
      <a href="gioithieuuser.php">ⓘ Giới thiệu</a>
      <a href="sanphamuser.php">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="../text.php">🧾 Đơn mua</a>
      <a href="giohangview.php">🛒 Giỏ hàng</a>
      <a href="logout.php" style="color: #dc3545;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">🚪 Đăng xuất</a>
    </div>
  </div>

  <div class="than_body">
    <div class="gioithieu">
      <h2>🛒 Giỏ hàng của bạn</h2>
      
      <?php if (!empty($_SESSION['cart'])): ?>
        <!-- Chọn tất cả -->
        <div class="select-all-section">
          <label class="select-all-label">
            <input type="checkbox" id="select-all" class="product-checkbox">
            <span>Chọn tất cả sản phẩm</span>
          </label>
        </div>

        <table>
          <tr>
            <th style="width: 60px;">Chọn</th>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
            <th>Hành động</th>
          </tr>
          <?php foreach ($_SESSION['cart'] as $id => $item): ?>
          <tr>
            <td>
              <input type="checkbox" 
                     class="product-checkbox item-checkbox" 
                     data-id="<?= $id ?>"
                     data-price="<?= $item['gia'] * $item['qty'] ?>">
            </td>
            <td><img src="<?= $item['anh'] ?? 'no-image.png' ?>"></td>
            <td><?= htmlspecialchars($item['ten']) ?></td>
            <td><?= $item['qty'] ?></td>
            <td><?= number_format($item['gia']) ?> VNĐ</td>
            <td class="item-total"><?= number_format($item['gia'] * $item['qty']) ?> VNĐ</td>
            <td>
              <form action="xoasanpham_giohang.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit" class="delete-btn">Xóa</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </table>

        <!-- Tổng tiền và nút mua hàng -->
        <div class="cart-footer">
          <div class="total-section">
            <div class="total-label">
              Tổng tiền (<span id="selected-count">0</span> sản phẩm):
            </div>
            <div class="total-amount" id="total-amount">0 VNĐ</div>
          </div>
          <button class="btn-checkout" id="checkout-btn" disabled onclick="checkoutSelected()">
            💳 Mua hàng
          </button>
        </div>

      <?php else: ?>
        <div class="empty-cart">
          <h3>🛒 Giỏ hàng trống</h3>
          <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm!</p>
          <a href="sanphamuser.php">🛍️ Mua sắm ngay</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Lấy các elements
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const totalAmountElement = document.getElementById('total-amount');
    const selectedCountElement = document.getElementById('selected-count');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Hàm tính tổng tiền
    function updateTotal() {
      let total = 0;
      let count = 0;
      
      itemCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
          total += parseFloat(checkbox.dataset.price);
          count++;
        }
      });
      
      // Hiển thị tổng tiền
      totalAmountElement.textContent = total.toLocaleString('vi-VN') + ' VNĐ';
      selectedCountElement.textContent = count;
      
      // Enable/disable nút mua hàng
      if (count > 0) {
        checkoutBtn.disabled = false;
      } else {
        checkoutBtn.disabled = true;
      }
    }

    // Sự kiện chọn tất cả
    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
        });
        updateTotal();
      });
    }

    // Sự kiện chọn từng item
    itemCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        updateTotal();
        
        // Cập nhật trạng thái checkbox "Chọn tất cả"
        const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
        if (selectAllCheckbox) {
          selectAllCheckbox.checked = allChecked;
        }
      });
    });

    // Hàm xử lý mua hàng
    function checkoutSelected() {
      const selectedIds = [];
      
      itemCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
          selectedIds.push(checkbox.dataset.id);
        }
      });
      
      if (selectedIds.length === 0) {
        alert('⚠️ Vui lòng chọn ít nhất 1 sản phẩm!');
        return;
      }
      
      // Chuyển sang trang đặt hàng với các sản phẩm đã chọn
      window.location.href = '../text.php?ids=' + selectedIds.join(',');
    }
  </script>
</body>
</html>