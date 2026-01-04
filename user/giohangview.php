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
    .header {
  height: 75px;
  padding: 0 40px;
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

    .modal {
  display: none;
  position: fixed;
  z-index: 3000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6);
}

.modal-content {
  background: #fff;
  width: 800px;
  max-width: 90%;
  margin: 100px auto;
  padding: 25px;
  border-radius: 10px;
}

.modal-content h3 {
  text-align: center;
  margin-bottom: 20px;
  color: #2e7d32;
}

.form-row {
  display: flex;
  gap: 30px;
  margin-bottom: 20px;
}

.form-col-left {
  flex: 1;
}

.form-col-right {
  flex: 1;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-weight: 600;
  display: block;
  margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #2e7d32;
  box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.1);
}

.form-group input.error,
.form-group textarea.error {
  border-color: #dc3545;
  background-color: #fff5f5;
}

.error-message {
  color: #dc3545;
  font-size: 13px;
  margin-top: 5px;
  display: none;
}

.error-message.show {
  display: block;
}

.payment-method {
  margin-bottom: 12px;
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  transition: all 0.3s;
}

.payment-method:hover {
  border-color: #2e7d32;
  background: #f1f8f4;
}

.payment-method label {
  display: flex;
  align-items: center;
  cursor: pointer;
  font-weight: 500;
}

.payment-method input[type="radio"] {
  margin-right: 10px;
  width: 18px;
  height: 18px;
  accent-color: #2e7d32;
  cursor: pointer;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.modal-actions button {
  padding: 10px 18px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: 600;
}

.modal-actions button[type="submit"] {
  background: #2e7d32;
  color: white;
}

.modal-actions button[type="button"] {
  background: #ccc;
}
  </style>
</head>
<body>
  <div class="header">
    <div class="logo">🌿 Green Tree</div>
    <div class="header-right">
      <div class="contact">📞 0345 530 628</div>
      <div class="nav_login"><a href="../login/index.php">👤 Đăng kí / Đăng nhập</a></div>
    </div>
  </div>

  <div class="nav">
    <div class="nav-left">
      <a href="user.php">🏠️ Trang chủ</a>
      <a href="gioithieuusser.php">ⓘ Giới thiệu</a>
      <a href="sanphamuser.php">🛍️ Sản phẩm</a>
    </div>
    <div class="nav-right">
      <a href="donhang.php">🧾 Đơn mua</a>
      <a href="giohangview.php" class="active">🛒 Giỏ hàng</a>
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
              <form action="xoasanpham_giohang.php" method="POST" style="display:inline;" onsubmit="return confirm('❓ Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">
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


  <!-- MODAL ĐẶT HÀNG -->
<!-- MODAL ĐẶT HÀNG -->
<div id="checkout-modal" class="modal">
  <div class="modal-content">
    <h3>📦 Thông tin đặt hàng</h3>

    <form action="xuly_dathang.php" method="POST">
      <!-- ID sản phẩm đã chọn -->
      <input type="hidden" name="product_ids" id="product_ids">

      <div class="form-row">
        <!-- Cột trái: Thông tin khách hàng -->
        <div class="form-col-left">
          <div class="form-group">
            <label>Họ và tên <span style="color:red;">*</span></label>
            <input type="text" name="hoten" id="hoten" placeholder="Nhập họ và tên của bạn">
            <div class="error-message" id="error-hoten">⚠️ Vui lòng nhập họ và tên</div>
          </div>

          <div class="form-group">
            <label>Số điện thoại <span style="color:red;">*</span></label>
            <input type="text" name="sdt" id="sdt" placeholder="Nhập số điện thoại (10-11 chữ số)">
            <div class="error-message" id="error-sdt">⚠️ Vui lòng nhập số điện thoại hợp lệ</div>
          </div>

          <div class="form-group">
            <label>Địa chỉ nhận hàng <span style="color:red;">*</span></label>
            <textarea name="diachi" id="diachi" rows="3" placeholder="Nhập địa chỉ chi tiết để giao hàng"></textarea>
            <div class="error-message" id="error-diachi">⚠️ Vui lòng nhập địa chỉ nhận hàng</div>
          </div>

          <div class="form-group">
            <label>Ghi chú</label>
            <textarea name="ghichu" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
          </div>
        </div>

        <!-- Cột phải: Phương thức thanh toán -->
        <div class="form-col-right">
          <div class="form-group">
            <label>Phương thức thanh toán</label>

            <div class="payment-method">
              <label>
                <input type="radio" name="payment_method" value="cod" checked>
                🚚 Thanh toán khi nhận hàng (COD)
              </label>
            </div>

            <div class="payment-method">
              <label>
                <input type="radio" name="payment_method" value="vnpay">
                💳 Thanh toán qua VNPay
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" onclick="closeModal()">❌ Hủy</button>
        <button type="button" onclick="submitOrder()">✅ Xác nhận đặt hàng</button>
      </div>
    </form>
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

  document.querySelectorAll('.item-checkbox').forEach(cb => {
    if (cb.checked) {
      selectedIds.push(cb.dataset.id);
    }
  });

  if (selectedIds.length === 0) {
    alert('⚠️ Vui lòng chọn ít nhất 1 sản phẩm');
    return;
  }

  document.getElementById('product_ids').value = selectedIds.join(',');
  document.getElementById('checkout-modal').style.display = 'block';
}

function closeModal() {
  document.getElementById('checkout-modal').style.display = 'none';
}
      
      // Chuyển sang trang đặt hàng với các sản phẩm đã chọn
      //window.location.href = '../text.php?ids=' + selectedIds.join(',');

  function submitOrder() {
  // Reset tất cả lỗi trước
  document.querySelectorAll('.error-message').forEach(el => el.classList.remove('show'));
  document.querySelectorAll('input, textarea').forEach(el => el.classList.remove('error'));

  let hasError = false;

  // Lấy giá trị từ các trường
  const hoten = document.getElementById('hoten').value.trim();
  const sdt = document.getElementById('sdt').value.trim();
  const diachi = document.getElementById('diachi').value.trim();

  // Kiểm tra họ và tên
  if (!hoten) {
    document.getElementById('hoten').classList.add('error');
    document.getElementById('error-hoten').classList.add('show');
    document.getElementById('error-hoten').textContent = '⚠️ Vui lòng nhập họ và tên';
    hasError = true;
    if (!hasError) document.getElementById('hoten').focus();
  } else if (hoten.length < 2) {
    document.getElementById('hoten').classList.add('error');
    document.getElementById('error-hoten').classList.add('show');
    document.getElementById('error-hoten').textContent = '⚠️ Họ và tên phải có ít nhất 2 ký tự';
    hasError = true;
    if (!hasError) document.getElementById('hoten').focus();
  }

  // Kiểm tra số điện thoại
  if (!sdt) {
    document.getElementById('sdt').classList.add('error');
    document.getElementById('error-sdt').classList.add('show');
    document.getElementById('error-sdt').textContent = '⚠️ Vui lòng nhập số điện thoại';
    hasError = true;
  } else {
    // Kiểm tra định dạng số điện thoại (10-11 số)
    const phoneRegex = /^[0-9]{10,11}$/;
    if (!phoneRegex.test(sdt)) {
      document.getElementById('sdt').classList.add('error');
      document.getElementById('error-sdt').classList.add('show');
      document.getElementById('error-sdt').textContent = '⚠️ Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số';
      hasError = true;
    }
  }

  // Kiểm tra địa chỉ
  if (!diachi) {
    document.getElementById('diachi').classList.add('error');
    document.getElementById('error-diachi').classList.add('show');
    document.getElementById('error-diachi').textContent = '⚠️ Vui lòng nhập địa chỉ nhận hàng';
    hasError = true;
  } else if (diachi.length < 10) {
    document.getElementById('diachi').classList.add('error');
    document.getElementById('error-diachi').classList.add('show');
    document.getElementById('error-diachi').textContent = '⚠️ Địa chỉ quá ngắn! Vui lòng nhập địa chỉ chi tiết';
    hasError = true;
  }

  // Nếu có lỗi, dừng lại và hiển thị thông báo
  if (hasError) {
    alert('⚠️ Vui lòng điền đầy đủ và chính xác thông tin!');
    return;
  }

  // Nếu tất cả thông tin đều hợp lệ, tiến hành submit
  const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
  const form = document.querySelector('#checkout-modal form');

  if (paymentMethod === 'vnpay') {
    // 👉 VNPay: chuyển sang trang thanh toán
    form.action = "thanhtoan.php";
  } else {
    // 👉 COD: xử lý đặt hàng bình thường
    form.action = "xuly_dathang.php";
  }

  form.submit();
}

// Thêm validation real-time khi người dùng nhập liệu
document.addEventListener('DOMContentLoaded', function() {
  const hotenInput = document.getElementById('hoten');
  const sdtInput = document.getElementById('sdt');
  const diachiInput = document.getElementById('diachi');

  if (hotenInput) {
    hotenInput.addEventListener('input', function() {
      if (this.value.trim()) {
        this.classList.remove('error');
        document.getElementById('error-hoten').classList.remove('show');
      }
    });
  }

  if (sdtInput) {
    sdtInput.addEventListener('input', function() {
      const phoneRegex = /^[0-9]{10,11}$/;
      if (phoneRegex.test(this.value.trim())) {
        this.classList.remove('error');
        document.getElementById('error-sdt').classList.remove('show');
      }
    });
  }

  if (diachiInput) {
    diachiInput.addEventListener('input', function() {
      if (this.value.trim().length >= 10) {
        this.classList.remove('error');
        document.getElementById('error-diachi').classList.remove('show');
      }
    });
  }
});
    
  </script>
</body>
</html>