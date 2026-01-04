<?php
require "../config/database.php";

// Kiểm tra ID đơn hàng
if (!isset($_GET['id'])) {
    header("Location: danhsachdonhang.php");
    exit();
}

$donhang_id = intval($_GET['id']);

// Đếm tổng số đơn hàng chờ xác nhận
$sqlDonHangCho = "SELECT COUNT(*) AS tong_dh_cho FROM donhang WHERE trang_thai = 'Chờ xác nhận'";
$tongDonHangCho = excuteResult($sqlDonHangCho)[0]['tong_dh_cho'];

// Lấy thông tin đơn hàng
$sql = "
SELECT 
    dh.*,
    nd.tai_khoan,
    nd.email,
    nd.so_dien_thoai as sdt_user
FROM donhang dh
LEFT JOIN nguoidung nd ON dh.nguoidung_id = nd.id
WHERE dh.id = $donhang_id
";
$order = excuteResult($sql);

if (empty($order)) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location.href='danhsachdonhang.php';</script>";
    exit();
}
$order = $order[0];

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_chitiet = "
SELECT 
    dc.*,
    sp.ten as ten_sanpham,
    sp.url as anh_sanpham
FROM donhang_chitiet dc
LEFT JOIN sanpham sp ON dc.sanpham_id = sp.id
WHERE dc.donhang_id = $donhang_id
";
$chitiet = excuteResult($sql_chitiet);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chi tiết đơn hàng #<?= $donhang_id ?></title>

<style>
    body {
        margin: 0;
        background: #f3f4f6;
        font-family: "Segoe UI", sans-serif;
        display: flex;
    }

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
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        margin-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .info-label {
        font-weight: 600;
        width: 200px;
        color: #555;
    }

    .info-value {
        flex: 1;
        color: #333;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-confirmed { background: #d1ecf1; color: #0c5460; }
    .status-shipping { background: #cce5ff; color: #004085; }
    .status-completed { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        background: #f9fafb;
        font-weight: 600;
        color: #6b7280;
    }

    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        margin: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .total-section {
        text-align: right;
        padding: 20px;
        border-top: 2px solid #e5e7eb;
        margin-top: 20px;
    }

    .total-amount {
        font-size: 24px;
        font-weight: bold;
        color: #2e7d32;
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
        <div class="header-title">📋 Chi tiết đơn hàng #<?= $donhang_id ?></div>

        <!-- Thông tin đơn hàng -->
        <div class="box">
            <h3 style="color: #2e7d32; margin-bottom: 20px;">📦 Thông tin đơn hàng</h3>
            
            <div class="info-row">
                <div class="info-label">Mã đơn hàng:</div>
                <div class="info-value"><strong>#<?= $order['id'] ?></strong></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Ngày đặt:</div>
                <div class="info-value"><?= date('d/m/Y H:i', strtotime($order['ngay_tao'])) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Trạng thái:</div>
                <div class="info-value">
                    <?php
                    $status_class = 'status-pending';
                    switch($order['trang_thai']) {
                        case 'Đã xác nhận': $status_class = 'status-confirmed'; break;
                        case 'Đang giao': $status_class = 'status-shipping'; break;
                        case 'Đã giao': $status_class = 'status-completed'; break;
                        case 'Đã hủy': $status_class = 'status-cancelled'; break;
                    }
                    ?>
                    <span class="status-badge <?= $status_class ?>">
                        <?= htmlspecialchars($order['trang_thai']) ?>
                    </span>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Phương thức thanh toán:</div>
                <div class="info-value"><?= htmlspecialchars($order['phuong_thuc_tt']) ?></div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="box">
            <h3 style="color: #2e7d32; margin-bottom: 20px;">👤 Thông tin khách hàng</h3>
            
            <div class="info-row">
                <div class="info-label">Tài khoản:</div>
                <div class="info-value"><?= htmlspecialchars($order['tai_khoan']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?= htmlspecialchars($order['email']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Người nhận:</div>
                <div class="info-value"><?= htmlspecialchars($order['ten_nguoinhan']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value"><?= htmlspecialchars($order['so_dien_thoai']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Địa chỉ giao hàng:</div>
                <div class="info-value"><?= htmlspecialchars($order['dia_chi_giao']) ?></div>
            </div>
        </div>

        <!-- Sản phẩm trong đơn hàng -->
        <div class="box">
            <h3 style="color: #2e7d32; margin-bottom: 20px;">🛍️ Sản phẩm đã đặt</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chitiet as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['anh_sanpham'])): ?>
                                <img src="<?= htmlspecialchars($item['anh_sanpham']) ?>" class="product-img" alt="">
                            <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #ddd; border-radius: 8px;"></div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($item['ten_sanpham']) ?></strong></td>
                        <td><?= number_format($item['don_gia'], 0, ',', '.') ?>đ</td>
                        <td><?= $item['so_luong'] ?></td>
                        <td style="color: #2e7d32; font-weight: 600;">
                            <?= number_format($item['don_gia'] * $item['so_luong'], 0, ',', '.') ?>đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="total-section">
                <div style="font-size: 16px; color: #666; margin-bottom: 10px;">
                    Tổng cộng:
                </div>
                <div class="total-amount">
                    <?= number_format($order['tong_tien'], 0, ',', '.') ?>đ
                </div>
            </div>
        </div>

        <!-- Nút hành động -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="danhsachdonhang.php" class="btn btn-back">⬅️ Quay lại</a>
            
            <?php if ($order['trang_thai'] == 'Chờ xác nhận'): ?>
                <a href="xacnhandonhang.php?id=<?= $donhang_id ?>&action=confirm" 
                   class="btn btn-success"
                   onclick="return confirm('Xác nhận đơn hàng này?')">
                    ✅ Xác nhận đơn hàng
                </a>
                <a href="xacnhandonhang.php?id=<?= $donhang_id ?>&action=cancel" 
                   class="btn btn-danger"
                   onclick="return confirm('Hủy đơn hàng này?')">
                    ❌ Hủy đơn hàng
                </a>
            <?php elseif ($order['trang_thai'] == 'Đã xác nhận'): ?>
                <a href="xacnhandonhang.php?id=<?= $donhang_id ?>&action=shipping" 
                   class="btn btn-info"
                   onclick="return confirm('Đánh dấu đang giao hàng?')">
                    🚚 Đang giao hàng
                </a>
            <?php elseif ($order['trang_thai'] == 'Đang giao'): ?>
                <a href="xacnhandonhang.php?id=<?= $donhang_id ?>&action=complete" 
                   class="btn btn-success"
                   onclick="return confirm('Đánh dấu đã giao hàng thành công?')">
                    ✅ Đã giao hàng
                </a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
