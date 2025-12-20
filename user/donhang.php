<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$sql = "SELECT * FROM donhang WHERE nguoidung_id = $user_id ORDER BY ngay_tao DESC";
$orders = excuteResult($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - Green Tree</title>
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
        }

        /* Nav */
        .nav {
            position: fixed;
            top: 75px;
            left: 0; right: 0;
            z-index: 999;
            display: flex;
            justify-content: space-between;
            background-color: #ffffff;
            padding: 12px 40px;
            border-top: 1px solid #eee;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav a:hover {
            color: #2e7d32;
        }
        
        .container {
            max-width: 1200px;
            margin: 150px auto 50px;
            padding: 20px;
        }
        
        .order-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .order-id {
            font-size: 18px;
            font-weight: 600;
            color: #2e7d32;
        }
        
        .status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .status.pending { background: #fff3cd; color: #856404; }
        .status.confirmed { background: #d1ecf1; color: #0c5460; }
        .status.shipping { background: #cce5ff; color: #004085; }
        .status.completed { background: #d4edda; color: #155724; }
        .status.cancelled { background: #f8d7da; color: #721c24; }
        
        .order-info {
            margin: 15px 0;
        }
        
        .order-info p {
            margin: 8px 0;
            color: #555;
        }
        
        .order-total {
            font-size: 20px;
            font-weight: 600;
            color: #2e7d32;
            text-align: right;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
            margin-top: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn-detail {
            background: #2e7d32;
            color: #fff;
        }
        
        .btn-detail:hover {
            background: #1b5e20;
        }
        
        .btn-cancel {
            background: #dc3545;
            color: #fff;
        }
        
        .btn-cancel:hover {
            background: #c82333;
        }
        
        .empty-order {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .empty-order h2 {
            color: #2e7d32;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🌿 Green Tree</div>
    </div>

    <div class="nav">
        <div>
            <a href="user.php">🏠️ Trang chủ</a>
            <a href="sanphamuser.php">🛍️ Sản phẩm</a>
            <a href="donhang.php">🧾 Đơn hàng</a>
            <a href="giohangview.php">🛒 Giỏ hàng</a>
        </div>
    </div>

    <div class="container">
        <h1 style="color: #2e7d32; margin-bottom: 30px;">🧾 Đơn hàng của tôi</h1>
        
        <?php if (empty($orders)): ?>
            <div class="empty-order">
                <h2>📦 Bạn chưa có đơn hàng nào</h2>
                <p>Hãy mua sắm để trải nghiệm dịch vụ của chúng tôi!</p>
                <a href="sanphamuser.php" class="btn btn-detail">🛍️ Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <?php
                // Xác định class cho status
                $status_class = 'pending';
                $trang_thai = $order['trang_thai'] ?? 'Chờ xác nhận';
                
                switch($trang_thai) {
                    case 'Đã xác nhận': $status_class = 'confirmed'; break;
                    case 'Đang giao': $status_class = 'shipping'; break;
                    case 'Đã giao': $status_class = 'completed'; break;
                    case 'Đã hủy': $status_class = 'cancelled'; break;
                }
                ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">Đơn hàng #<?php echo $order['id']; ?></div>
                        <div class="status <?php echo $status_class; ?>">
                            <?php echo $trang_thai; ?>
                        </div>
                    </div>
                    
                    <div class="order-info">
                        <p><strong>📅 Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['ngay_tao'])); ?></p>
                        <p><strong>👤 Người nhận:</strong> <?php echo $order['ten_nguoinhan']; ?></p>
                        <p><strong>📞 Số điện thoại:</strong> <?php echo $order['so_dien_thoai']; ?></p>
                        <p><strong>📍 Địa chỉ:</strong> <?php echo $order['dia_chi_giao']; ?></p>
                        <p><strong>💳 Thanh toán:</strong> <?php echo $order['phuong_thuc_tt']; ?></p>
                    </div>
                    
                    <div class="order-total">
                        💰 Tổng tiền: <?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ
                    </div>
                    
                    <div style="text-align: right; margin-top: 15px;">
                        <a href="chitietdonhang.php?id=<?php echo $order['id']; ?>" class="btn btn-detail">
                            👁️ Xem chi tiết
                        </a>
                        <?php if ($trang_thai == 'Chờ xác nhận' || empty($trang_thai)): ?>
                            <a href="huydonhang.php?id=<?php echo $order['id']; ?>" 
                               class="btn btn-cancel"
                               onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                ❌ Hủy đơn
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>