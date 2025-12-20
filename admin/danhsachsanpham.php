<?php
require "../config/database.php";

// Lấy danh sách sản phẩm + danh mục + nhà cung cấp + ảnh
$sql = "
SELECT 
    sp.id,
    sp.ten,
    sp.gia,
    sp.so_luong,
    dm.ten AS ten_danhmuc,
    ncc.ten AS ten_ncc,
    ha.url AS anh
FROM SanPham sp
LEFT JOIN DanhMuc dm ON sp.danhmuc_id = dm.id
LEFT JOIN NhaCungCap ncc ON sp.nhacungcap_id = ncc.id
LEFT JOIN HinhAnh ha ON ha.sanpham_id = sp.id
GROUP BY sp.id
ORDER BY sp.id DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin - Danh sách sản phẩm</title>

<style>
    body {
        margin: 0;
        background: #f3f4f6;
        font-family: "Segoe UI", sans-serif;
        display: flex;
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

.sidebar-item {
  padding: 10px 12px;
  border-radius: 8px;
  cursor: pointer;
  margin-bottom: 5px;
  font-size: 17px;
  font-weight: bold;
  color: #444;
}

.sidebar-item:hover {
  background: #f3f4f6;
}

.sidebar-active {
  background: #e6f4ea;
  font-weight: 700;
}
.sidebar a{
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


    /* MAIN */
    .main {
        flex: 1;
        padding: 40px;
    }

    h2 {
        margin: 0 0 20px 0;
        font-size: 28px;
        color: #1f2937;
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

    img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
    }

    .edit-btn,
    .delete-btn {
        padding: 6px 10px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
    }
    .edit-btn { background: #2196F3; }
    .delete-btn { background: #E53935; }

</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="logo">
        <div style="width: 40px; height: 40px; background: #d0dcd5ff; color: #fff; border-radius: 50%;
        display:flex; align-items:center; justify-content:center; font-weight:700;">
            🌿
        </div>
        <span style="font-weight: 600; font-size:20px;color: #037a21ff">Green Tree Admin</span>
    </div>

    <hr>

    <div style="padding-left: 10px;">
        <a href="../admin/admin.php"><div class="sidebar-item">Trang Chủ</div></a>
        <a href="#"><div class="sidebar-item">Tài Khoản</div></a>
        <a href="../admin/danhsachnguoidung.php"><div class="sidebar-item">Người Dùng</div></a>
        <a href="../admin/danhsachsanpham.php"><div class="sidebar-item sidebar-active">Sản Phẩm</div></a>
        <a href="../admin/danhsachncc.php"><div class="sidebar-item">Nhà Cung Cấp</div></a>
    </div>

    <div>
        <a href="../login/index.php">
            <button class="logout-btn" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?')">
                Đăng xuất
            </button>
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="main">
    <h2>Danh sách sản phẩm</h2>
    <div style="text-align: right; padding-bottom: 15px;">
           <a href="themsanpham.php"><button style="
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
            onmouseout="this.style.backgroundColor='#4CAF50'">Thêm sản phẩm mới</button></a>
        </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Nhà cung cấp</th>
            <th>Số lượng</th>
            <th>Hành động</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>

            <td>
                <img src="<?= $row['anh'] ?? 'no-image.png' ?>">
            </td>

            <td><?= $row['ten'] ?></td>
            <td><?= $row['ten_danhmuc'] ?></td>
            <td><?= number_format($row['gia']) ?> VNĐ</td>
            <td><?= $row['ten_ncc'] ?></td>
            <td><?= $row['so_luong'] ?></td>

            <td>
                <a class="edit-btn" href="suasanpham.php?id=<?= $row['id'] ?>">Sửa</a>
                <a class="delete-btn" href="xoasanpham.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn chắc muốn xóa?')">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</main>

</body>
</html>




