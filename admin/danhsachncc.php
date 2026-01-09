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

// Lấy danh sách nhà cung cấp
$sql = "
    SELECT 
        id,
        ten,
        dia_chi,
        email,
        mo_ta,
        so_dien_thoai
    FROM nhacungcap
    ORDER BY id DESC
";
$nhacungcaps = excuteResult($sql);
$editNCC = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sqlEdit = "SELECT * FROM nhacungcap WHERE id = $id";
    $editNCC = excuteResult($sqlEdit);
    if ($editNCC) $editNCC = $editNCC[0];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Green Tree - Nhà Cung Cấp</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: #f3f4f6;
    }
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
    .sidebar-item {
      padding: 10px 12px;
      border-radius: 8px;
      cursor: pointer;
      margin-bottom: 5px;
      font-size: 17px;
      font-weight: bold;
      color: #444;
    }
    .sidebar-item:hover { background: #f3f4f6; }
    .sidebar-active { background: #e6f4ea; font-weight: 600; }
    
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
    
    .main { flex: 1; padding: 40px; }
    .header-title { font-size: 28px; font-weight: 700; color: #1f2937; }
    .box { margin-top: 24px; background: #fff; padding: 20px 30px; border-radius: 10px; border: 1px solid #e5e7eb; }
    table { width: 100%; margin: 30px auto; border-collapse: collapse; font-size: 16px; }
    th, td { padding: 14px 16px; border: 1px solid #999; text-align: left; vertical-align: middle; }
    th { background: #eee; font-size: 17px; }
    th:last-child, td:last-child { width: 12%; white-space: nowrap; }
    tr:hover { background: #f9fafb; }
    .action-buttons { display: flex; gap: 10px; align-items: center; justify-content: flex-start; }
    a.btn { padding: 6px 12px; text-decoration: none; border-radius: 5px; color: white; display: inline-block; }
    .btn-edit { background: #3498db; }
    .btn-delete { background: #e74c3c; }
    .logout-btn {
      display: block; width: 100%; padding: 10px 16px;
      background: #e53935; color: #fff; font-weight: 600;
      border: none; border-radius: 8px; cursor: pointer;
      font-size: 15px; margin-top: 20px;
    }
    .logout-btn:hover { background: #c62828; }
    .sidebar a { text-decoration: none; }

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

    .main h2 {
        margin: 0 0 20px 0;
        font-size: 28px;
        color: #1f2937;
    }

    .btn_add {
    background: #00a86b; /* xanh ngọc đẹp */
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
  </style>
</head>
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
        <a href="danhsachnguoidung.php"><div class="sidebar-item">Người Dùng</div></a>
        <a href="danhsachsanpham.php"><div class="sidebar-item">Sản Phẩm</div></a>
        <a href="danhsachncc.php"><div class="sidebar-item sidebar-active">Nhà Cung Cấp</div></a>
        <a href="danhsachdonhang.php">
          <div class="sidebar-item sidebar-item-with-badge">
            <span>Đơn Hàng</span>
            <?php if ($tongDonHangCho > 0): ?>
              <span class="badge"><?= $tongDonHangCho ?></span>
            <?php endif; ?>
          </div>
        </a>
      </div>
      <div>
        <a href="../index.php"><button class="logout-btn">Đăng xuất</button></a>
      </div>
    </aside>

    <!-- Main -->
    <main class="main">
      <h2>Danh sách nhà cung cấp</h2>
      <div style="text-align: right; padding-bottom: 15px;">
        <a href="themnhacungcap.php"><button style="
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
            onmouseout="this.style.backgroundColor='#410badff'">Thêm nhà cung cấp</button></a>
      </div>

        <table>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Địa chỉ</th>
            <th>Email</th>
            <th>Mô tả</th>
            <th>Số điện thoại</th>
            <th>Hành động</th>
          </tr>

          <?php foreach ($nhacungcaps as $ncc) : ?>
          <tr>
            <td><?= $ncc['id'] ?></td>
            <td><?= $ncc['ten'] ?></td>
            <td><?= $ncc['dia_chi'] ?></td>
            <td><?= $ncc['email'] ?></td>
            <td><?= $ncc['mo_ta'] ?></td>
            <td><?= $ncc['so_dien_thoai'] ?></td>
            <td>
              <a class="edit-btn" href="suancc.php?id=<?= $ncc['id'] ?>">Sửa</a>
              <a class="delete-btn"
                 href="xoanhacungcap.php?id=<?= $ncc['id'] ?>"
                 onclick="return confirm('Bạn có chắc muốn xóa nhà cung cấp này?');">
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