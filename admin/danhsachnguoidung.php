<?php
session_start();
require_once '../config/database.php';

// Kiểm tra quyền admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
//     header("Location: ../index.php");
//     exit();
// }

// Đếm tổng số đơn hàng chờ xác nhận
$sqlDonHangCho = "SELECT COUNT(*) AS tong_dh_cho FROM donhang WHERE trang_thai = 'Chờ xác nhận'";
$tongDonHangCho = excuteResult($sqlDonHangCho)[0]['tong_dh_cho'];

// Lấy danh sách người dùng
$sql = "
    SELECT 
        id,
        CONCAT(ho, ' ', ten) AS ho_ten,
        email,
        CASE 
            WHEN vaitro_id = 1 THEN 'Admin'
            WHEN vaitro_id = 2 THEN 'User'
            ELSE 'Không xác định'
        END AS vai_tro,
        so_dien_thoai,
        ngay_tao
    FROM NguoiDung
    WHERE trang_thai = 1
    ORDER BY id DESC
";
$users = excuteResult($sql);
$editUser = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sqlEdit = "SELECT * FROM NguoiDung WHERE id = $id";
    $editUser = excuteResult($sqlEdit);
    if ($editUser) $editUser = $editUser[0];
}

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

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
    }

    th {
        background: #4CAF50;
        color: white;
        padding: 12px;
        text-align: center;
        font-size: 15px;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        text-align: center;
        font-size: 14px;
    }

    tr:hover {
        background: #f9fafb;
    }

    .edit-btn,
    .delete-btn {
        padding: 6px 10px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        display: inline-block;
    }
    .edit-btn { background: #2196F3; }
    .delete-btn { background: #E53935; }

.btn_add {
    background: #00a86b;
    color: white;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.25s ease;
    box-shadow: 0px 3px 7px rgba(0, 0, 0, 0.15);
}

.btn_add:hover {
    background: #008f5b;
    transform: translateY(-2px);
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
}

.btn_add:active {
    transform: scale(0.95);
}

.main h2 {
    margin: 0 0 20px 0;
    font-size: 28px;
    color: #1f2937;
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
        <a href="admin.php"><div class="sidebar-item">Trang Chủ</div></a>
        <a href="danhsachnguoidung.php"><div class="sidebar-item sidebar-active">Người Dùng</div></a>
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
        <a href="../login/index.php"><button class="logout-btn" onclick="return confirmLogout();">
            Đăng xuất</button></a>
            
      </div>
    </aside>

    <!-- Main -->
   <main class="main">
    <h2>Danh sách người dùng</h2>
    <div style="text-align: right; padding-bottom: 15px;">
        <a href="themnguoidung.php"><button style="
                background-color: #410badff;
                color: white;
                padding: 10px 20px;
                border-radius: 6px;
                border: none;
                cursor: pointer;
                font-weight: 600;
                transition: 0.2s;
            " 
            onmouseover="this.style.backgroundColor='#58baecff'" 
            onmouseout="this.style.backgroundColor='#410badff'">Thêm người dùng</button></a>
    </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Số điện thoại</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>

            <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['ho_ten'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['vai_tro'] ?></td>
                <td><?= $user['so_dien_thoai'] ?></td>
                <td><?= $user['ngay_tao'] ?></td>
                <td>
                    <a class="edit-btn" href="suanguoidung.php?id=<?= $user['id'] ?>">Sửa</a>
                    <a class="delete-btn"
                       href="xoanguoidung.php?id=<?= $user['id'] ?>"
                       onclick="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                       Xóa
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
</main>

  </div>
</body>
</html>
