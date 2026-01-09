<?php
require_once '../config/database.php';

// Đếm tổng số sản phẩm
$sqlSanPham = "SELECT COUNT(*) AS tong_sp FROM sanpham";
$tongSanPham = excuteResult($sqlSanPham)[0]['tong_sp'];

// Đếm tổng số người dùng
$sqlNguoiDung = "SELECT COUNT(*) AS tong_nd FROM nguoidung WHERE trang_thai = 1";
$tongNguoiDung = excuteResult($sqlNguoiDung)[0]['tong_nd'];

// Đếm tổng số nhà cung cấp
$sqlNCC = "SELECT COUNT(*) AS tong_ncc FROM nhacungcap";
$tongNCC = excuteResult($sqlNCC)[0]['tong_ncc'];

// Đếm tổng số đơn hàng chờ xác nhận
$sqlDonHangCho = "SELECT COUNT(*) AS tong_dh_cho FROM donhang WHERE trang_thai = 'Chờ xác nhận'";
$tongDonHangCho = excuteResult($sqlDonHangCho)[0]['tong_dh_cho'];

?>




<!-- Layout admin + CSS riêng dễ chỉnh sửa -->
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Green Tree</title>

  <!-- CSS Tùy chỉnh -->
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: #f3f4f6;
    }

    /* SIDEBAR */
    .sidebar {
      width: 240px;
      background: #fff;
      border-right: 1px solid #e5e7eb;
      padding: 24px;
    
    }

    .sidebar .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      font-size: 30px;
    }

    .sidebar-item,
    .sidebar-title {
      padding: 10px 12px;
      border-radius: 8px;
      cursor: pointer;
      margin-bottom: 5px;
      font-size: 14px;
      color: #444;
    }

    .sidebar-item:hover {
      background: #f3f4f6;
    }

    .sidebar-active {
      background: #e6f4ea;
      font-weight: 600;
    }

    .badge {
      background: #dc3545;
      color: white;
      border-radius: 10px;
      padding: 2px 8px;
      font-size: 12px;
      font-weight: bold;
      margin-left: 8px;
    }

    .sidebar-item-with-badge {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* MAIN CONTENT */
    .main {
      flex: 1;
      padding: 40px;
    }

    .header-title {
      font-size: 28px;
      font-weight: 700;
      color: #1f2937;
    }

    .btn-primary {
      background: #6d28d9;
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-size: 14px;
    }

    /* CONTAINER */
    .box {
      margin-top: 24px;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
    }

    /* FILTER + SEARCH */
    select,
    input[type="text"] {
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
      width: 100%;
    }

    .grid2 {
      display: grid;
      grid-template-columns: 200px 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th {
      text-align: left;
      padding: 12px;
      color: #6b7280;
      border-bottom: 1px solid #e5e7eb;
      font-weight: 600;
    }

    td {
      padding: 14px 12px;
      border-bottom: 1px solid #e5e7eb;
    }

    tr:hover {
      background: #f9fafb;
    }

    .product-img {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: #ddd;
    }
    .search-input {
      width: 150px;
      
    }
    .box{
      padding: 20px 30px;

    }
    .sidebar-item{
      font-weight: bold;
      font-size: 17px;
    }
    a {
      text-decoration: none;

    }
   .logout-btn {
  display: block;
  width: 100%;
  padding: 10px 16px;
  background: #e53935;
  color: #fff;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 15px;
  margin-top: 20px;
  transition: background 0.3s ease, transform 0.2s ease;
}

.logout-btn:hover {
  background: #c62828;
  transform: translateY(-2px);
}

.logout-btn:active {
  transform: scale(0.98);
}
.stats-container {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
}

.stat-box {
  flex: 1;
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stat-box h3 {
  font-size: 18px;
  color: #037a21;
  margin-bottom: 10px;
}

.stat-box p {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}
.stats-container {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
}

.stat-box {
  flex: 1;
  border-radius: 12px;
  padding: 25px;
  text-align: center;
  color: #fff;
  box-shadow: 0 6px 12px rgba(0,0,0,0.15);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-box h3 {
  font-size: 18px;
  margin-bottom: 12px;
}

.stat-box p {
  font-size: 28px;
  font-weight: bold;
  margin: 0;
}

/* Mỗi ô một màu */
.stat-products {
  background: linear-gradient(135deg, #4caf50, #2e7d32); /* xanh lá */
}

.stat-users {
  background: linear-gradient(135deg, #2196f3, #1565c0); /* xanh dương */
}

.stat-suppliers {
  background: linear-gradient(135deg, #ff9800, #e65100); /* cam */
}

/* Hiệu ứng hover */
.stat-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 18px rgba(0,0,0,0.2);
}


  </style>
</head>

<!-- đóng mở form thêm sản phẩm cho trang admin -->


<body>
  <div style="display: flex; min-height: 100vh;">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <div style="width: 40px; height: 40px; background: #d0dcd5ff; color: #fff; border-radius: 50%; display:flex; align-items:center; justify-content:center; font-weight:700;">
         🌿
         
        </div>
        <span style="font-weight: 600; font-size:20px;color: #037a21ff">Green Tree Admin</span>
        
      </div>

      <hr>


      <div style="padding-left: 10px;">
        <a href="admin.php"><div class="sidebar-item sidebar-active">Trang Chủ</div></a>
        <a href="danhsachnguoidung.php"><div class="sidebar-item">Người Dùng</div></a>
        <a href="danhsachsanpham.php"><div class="sidebar-item">Sản Phẩm</div></a>
        <a href="danhsachncc.php"><div class="sidebar-item">Nhà Cung Cấp</div></a>
        <a href="danhsachdonhang.php">
          <div class="sidebar-item sidebar-item-with-badge">
            <span>Đơn Hàng</span>
            <?php if ($tongDonHangCho > 0): ?>
              <span class="badge"><?= $tongDonHangCho ?></span>
            <?php endif; ?>
          </div>
        </a>
      </div>
      <div >
        <a href="../index.php"><button class="logout-btn" onclick="return confirmLogout();">
            Đăng xuất</button></a>
            
      </div>
    </aside>

    <!-- Main -->
    <main class="main">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        
       
                  <div class="stats-container">
                        <div class="stat-box" style="background-color: #1565c0;">
                            <h3 style="color: #feffffff;">Tổng số sản phẩm</h3>
                            <p><?= $tongSanPham ?></p>
                        </div>
                        <div class="stat-box" style="background-color: #ff5722;">
                            <h3 style="color: #feffffff;">Đơn hàng chờ xác nhận</h3>
                            <p><?= $tongDonHangCho ?></p>
                        </div>
                        <div class="stat-box" style="background-color: #08eb72ff;">
                            <h3 style="color: #feffffff;">Tổng số người dùng</h3>
                            <p><?= $tongNguoiDung ?></p>
                        </div>
                        <div class="stat-box" style="background-color: #c7f307ff;">
                            <h3 style="color: #feffffff;">Tổng số nhà cung cấp</h3>
                            <p><?= $tongNCC ?></p>
                        </div>
                    </div>

      </div>

    </main>
  </div>


  </div>
</div>
</body>

</html>
