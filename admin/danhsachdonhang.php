<?php
require "../config/database.php";

// Đếm tổng số đơn hàng chờ xác nhận
$sqlDonHangCho = "SELECT COUNT(*) AS tong_dh_cho FROM donhang WHERE trang_thai = 'Chờ xác nhận'";
$tongDonHangCho = excuteResult($sqlDonHangCho)[0]['tong_dh_cho'];

// Lấy danh sách đơn hàng
$sql = "
SELECT 
    dh.id,
    dh.ten_nguoinhan,
    dh.so_dien_thoai,
    dh.dia_chi_giao,
    dh.tong_tien,
    dh.phuong_thuc_tt,
    dh.trang_thai,
    dh.ngay_tao,
    nd.tai_khoan,
    nd.email
FROM donhang dh
LEFT JOIN nguoidung nd ON dh.nguoidung_id = nd.id
ORDER BY 
    CASE 
        WHEN dh.trang_thai = 'Chờ xác nhận' THEN 1
        WHEN dh.trang_thai = 'Đã xác nhận' THEN 2
        WHEN dh.trang_thai = 'Đang giao' THEN 3
        WHEN dh.trang_thai = 'Đã giao' THEN 4
        ELSE 5
    END,
    dh.ngay_tao DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin - Quản lý đơn hàng</title>

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
        min-height: 100vh;
    }

    .sidebar .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .sidebar-item {
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        margin-bottom: 5px;
        font-size: 17px;
        color: #444;
        font-weight: bold;
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
    }

    .logout-btn:hover {
        background: #c62828;
    }

    /* MAIN */
    .main {
        flex: 1;
        padding: 40px;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .box {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
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
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        background: #f9fafb;
    }

    td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-shipping {
        background: #cce5ff;
        color: #004085;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        margin: 2px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .filter-section {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    select {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }
</style>
</head>

<body>
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
            <a href="danhsachncc.php"><div class="sidebar-item">Nhà Cung Cấp</div></a>
            <a href="danhsachdonhang.php">
                <div class="sidebar-item sidebar-active sidebar-item-with-badge">
                    <span>Đơn Hàng</span>
                    <?php if ($tongDonHangCho > 0): ?>
                        <span class="badge"><?= $tongDonHangCho ?></span>
                    <?php endif; ?>
                </div>
            </a>
        </div>
        <div>
            <a href="../login/index.php"><button class="logout-btn">Đăng xuất</button></a>
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        <div class="header-title">📦 Quản lý đơn hàng</div>

        <div class="box">
            <div class="filter-section">
                <label><strong>Lọc theo trạng thái:</strong></label>
                <select id="filter-status" onchange="filterOrders()">
                    <option value="">Tất cả</option>
                    <option value="Chờ xác nhận">Chờ xác nhận</option>
                    <option value="Đã xác nhận">Đã xác nhận</option>
                    <option value="Đang giao">Đang giao</option>
                    <option value="Đã giao">Đã giao</option>
                    <option value="Đã hủy">Đã hủy</option>
                </select>
            </div>

            <table id="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Người nhận</th>
                        <th>SĐT</th>
                        <th>Địa chỉ</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            $status_class = 'status-pending';
                            switch($row['trang_thai']) {
                                case 'Đã xác nhận': $status_class = 'status-confirmed'; break;
                                case 'Đang giao': $status_class = 'status-shipping'; break;
                                case 'Đã giao': $status_class = 'status-completed'; break;
                                case 'Đã hủy': $status_class = 'status-cancelled'; break;
                            }
                            ?>
                            <tr data-status="<?= htmlspecialchars($row['trang_thai']) ?>">
                                <td><strong>#<?= $row['id'] ?></strong></td>
                                <td>
                                    <?= htmlspecialchars($row['tai_khoan']) ?><br>
                                    <small style="color: #6b7280;"><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['ten_nguoinhan']) ?></td>
                                <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                                <td style="max-width: 200px;">
                                    <small><?= htmlspecialchars($row['dia_chi_giao']) ?></small>
                                </td>
                                <td style="color: #2e7d32; font-weight: 600;">
                                    <?= number_format($row['tong_tien'], 0, ',', '.') ?>đ
                                </td>
                                <td><small><?= htmlspecialchars($row['phuong_thuc_tt']) ?></small></td>
                                <td>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= htmlspecialchars($row['trang_thai']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($row['ngay_tao'])) ?></td>
                                <td>
                                    <a href="chitietdonhang.php?id=<?= $row['id'] ?>" class="btn btn-info">
                                        👁️ Chi tiết
                                    </a>
                                    <?php if ($row['trang_thai'] == 'Chờ xác nhận'): ?>
                                        <a href="xacnhandonhang.php?id=<?= $row['id'] ?>&action=confirm" 
                                           class="btn btn-success"
                                           onclick="return confirm('Xác nhận đơn hàng này?')">
                                            ✅ Xác nhận
                                        </a>
                                        <a href="xacnhandonhang.php?id=<?= $row['id'] ?>&action=cancel" 
                                           class="btn btn-danger"
                                           onclick="return confirm('Hủy đơn hàng này?')">
                                            ❌ Hủy
                                        </a>
                                    <?php elseif ($row['trang_thai'] == 'Đã xác nhận'): ?>
                                        <a href="xacnhandonhang.php?id=<?= $row['id'] ?>&action=shipping" 
                                           class="btn btn-info"
                                           onclick="return confirm('Đánh dấu đang giao hàng?')">
                                            🚚 Đang giao
                                        </a>
                                    <?php elseif ($row['trang_thai'] == 'Đang giao'): ?>
                                        <a href="xacnhandonhang.php?id=<?= $row['id'] ?>&action=complete" 
                                           class="btn btn-success"
                                           onclick="return confirm('Đánh dấu đã giao hàng?')">
                                            ✅ Hoàn thành
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                                Chưa có đơn hàng nào
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function filterOrders() {
            const filterValue = document.getElementById('filter-status').value;
            const rows = document.querySelectorAll('#orders-table tbody tr');
            
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (filterValue === '' || status === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
