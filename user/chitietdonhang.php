<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$donhang_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đơn hàng
$sql_donhang = "SELECT * FROM donhang WHERE id = $donhang_id AND nguoidung_id = $user_id";
$donhang = excuteResult($sql_donhang);

if (empty($donhang)) {
    header("Location: donhang.php");
    exit();
}

$donhang = $donhang[0];

// Lấy chi tiết sản phẩm trong đơn hàng - JOIN với bảng sanpham để lấy tên và ảnh
$sql_chitiet = "SELECT dc.*, sp.ten as ten_sanpham, ha.url as anh_sanpham, 
                       (dc.so_luong * dc.don_gia) as thanh_tien
                FROM donhang_chitiet dc
                LEFT JOIN sanpham sp ON dc.sanpham_id = sp.id
                LEFT JOIN hinhanh ha ON sp.id = ha.sanpham_id
                WHERE dc.donhang_id = $donhang_id
                GROUP BY dc.id";
$chitiet = excuteResult($sql_chitiet);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?php echo $donhang_id; ?> - Green Tree</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f5;
        }
        
        /* Header */
        .header {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: 75px;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .container {
            max-width: 1000px;
            margin: 150px auto 50px;
            padding: 20px;
        }
        
        .detail-card {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #2e7d32;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .order-title {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
        }

        .status {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
        }
        
        .status.pending { background: #fff3cd; color: #856404; }
        .status.confirmed { background: #d1ecf1; color: #0c5460; }
        .status.shipping { background: #cce5ff; color: #004085; }
        .status.completed { background: #d4edda; color: #155724; }
        .status.cancelled { background: #f8d7da; color: #721c24; }

        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .info-item strong {
            display: block;
            color: #2e7d32;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-item span {
            color: #333;
            font-size: 15px;
        }

        /* Table sản phẩm */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background: #2e7d32;
            color: #fff;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .product-name {
            font-weight: 500;
            color: #333;
        }

        .total-section {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 3px solid #2e7d32;
        }

        .total-label {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .total-amount {
            font-size: 32px;
            font-weight: bold;
            color: #2e7d32;
        }

        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            background: #2e7d32;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-back:hover {
            background: #1b5e20;
            transform: translateY(-2px);
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
            <a href="user.php">🏠 Trang chủ</a>
            <a href="gioithieuusser.php">ⓘ Giới thiệu</a>
            <a href="sanphamuser.php">🛍️ Sản phẩm</a>
        </div>
        <div class="nav-right">
            <a href="donhang.php" class="active">🧾 Đơn mua</a>
            <a href="giohangview.php">🛒 Giỏ hàng</a>
            <a href="logout.php" style="color: #dc3545;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">🚪 Đăng xuất</a>
        </div>
    </div>

    <div class="container">
        <div class="detail-card">
            <div class="detail-header">
                <div class="order-title">📦 Đơn hàng #<?php echo $donhang_id; ?></div>
                <?php
                $status_class = 'pending';
                $trang_thai = $donhang['trang_thai'] ?? 'Chờ xác nhận';
                
                switch($trang_thai) {
                    case 'Đã xác nhận': $status_class = 'confirmed'; break;
                    case 'Đang giao': $status_class = 'shipping'; break;
                    case 'Đã giao': $status_class = 'completed'; break;
                    case 'Đã hủy': $status_class = 'cancelled'; break;
                }
                ?>
                <div class="status <?php echo $status_class; ?>"><?php echo $trang_thai; ?></div>
            </div>

            <div class="order-info">
                <div class="info-item">
                    <strong>📅 Ngày đặt hàng</strong>
                    <span><?php echo date('d/m/Y H:i', strtotime($donhang['ngay_tao'])); ?></span>
                </div>
                <div class="info-item">
                    <strong>💳 Phương thức thanh toán</strong>
                    <span><?php echo $donhang['phuong_thuc_tt']; ?></span>
                </div>
                <div class="info-item">
                    <strong>👤 Người nhận</strong>
                    <span><?php echo $donhang['ten_nguoinhan']; ?></span>
                </div>
                <div class="info-item">
                    <strong>📞 Số điện thoại</strong>
                    <span><?php echo $donhang['so_dien_thoai']; ?></span>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <strong>📍 Địa chỉ giao hàng</strong>
                    <span><?php echo $donhang['dia_chi_giao']; ?></span>
                </div>
                <?php if (!empty($donhang['ghi_chu'])): ?>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <strong>📝 Ghi chú</strong>
                    <span><?php echo $donhang['ghi_chu']; ?></span>
                </div>
                <?php endif; ?>
            </div>

            <h3 style="color: #2e7d32; margin-top: 30px; margin-bottom: 15px;">🛍️ Danh sách sản phẩm</h3>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 100px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 100px; text-align: center;">Số lượng</th>
                        <th style="width: 150px; text-align: right;">Đơn giá</th>
                        <th style="width: 150px; text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chitiet as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $item['anh_sanpham'] ?? 'no-image.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($item['ten_sanpham']); ?>" 
                                 class="product-img">
                        </td>
                        <td class="product-name"><?php echo htmlspecialchars($item['ten_sanpham']); ?></td>
                        <td style="text-align: center; font-weight: 600;"><?php echo $item['so_luong']; ?></td>
                        <td style="text-align: right;"><?php echo number_format($item['don_gia'], 0, ',', '.'); ?>đ</td>
                        <td style="text-align: right; font-weight: 600; color: #2e7d32;">
                            <?php echo number_format($item['thanh_tien'], 0, ',', '.'); ?>đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-label">Tổng cộng:</div>
                <div class="total-amount"><?php echo number_format($donhang['tong_tien'], 0, ',', '.'); ?>đ</div>
            </div>

            <a href="donhang.php" class="btn-back">← Quay lại danh sách đơn hàng</a>
        </div>
    </div>
</body>
</html>
