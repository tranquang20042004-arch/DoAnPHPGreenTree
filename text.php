<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$sql = "SELECT dh.*, dh.id as donhang_id 
        FROM donhang dh 
        WHERE dh.nguoidung_id = $user_id 
        ORDER BY dh.ngay_tao DESC";
$orders = excuteResult($sql);

// Gom thông tin đơn hàng
$grouped_orders = [];
foreach ($orders as $order) {
    $donhang_id = $order['id'];
    
    // Lấy chi tiết sản phẩm trong đơn hàng
    $sql_detail = "SELECT dc.*, s.ten as ten_sanpham, ha.url as anh_sanpham 
                   FROM donhang_chitiet dc 
                   LEFT JOIN sanpham s ON dc.sanpham_id = s.id
                   LEFT JOIN hinhanh ha ON ha.sanpham_id = s.id
                   WHERE dc.donhang_id = $donhang_id
                   GROUP BY dc.id";
    $details = excuteResult($sql_detail);
    
    if (!empty($details)) {
        $grouped_orders[$donhang_id] = [
            'donhang_id' => $donhang_id,
            'ngay_tao' => $order['ngay_tao'],
            'trang_thai' => $order['trang_thai'] ?? 'Đã đặt hàng',
            'tong_tien' => floatval($order['tong_tien']),
            'items' => $details
        ];
    }
}
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
            background: #d4edda;
            color: #155724;
        }
        
        .order-info {
            margin: 15px 0;
        }
        
        .order-info p {
            margin: 8px 0;
            color: #555;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin: 10px 0;
            background: #f8f9fa;
            border-radius: 8px;
            gap: 15px;
        }

        .product-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-info {
            flex-grow: 1;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .product-qty {
            color: #666;
            font-size: 14px;
        }

        .product-price {
            font-weight: 600;
            color: #2e7d32;
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

        .empty-order p {
            color: #666;
            margin-bottom: 25px;
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
            background: #2e7d32;
            color: #fff;
        }
        
        .btn:hover {
            background: #1b5e20;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🌿 Green Tree</div>
    </div>

    <div class="nav">
        <div>
            <a href="user/user.php">🏠️ Trang chủ</a>
            <a href="user/sanphamuser.php">🛍️ Sản phẩm</a>
            <a href="text.php">🧾 Đơn hàng</a>
            <a href="user/giohangview.php">🛒 Giỏ hàng</a>
        </div>
    </div>

    <div class="container">
        <h1 style="color: #2e7d32; margin-bottom: 30px;">🧾 Đơn hàng của tôi</h1>
        
        <?php if (empty($grouped_orders)): ?>
            <div class="empty-order">
                <h2>📦 Bạn chưa có đơn hàng nào</h2>
                <p>Hãy mua sắm để trải nghiệm dịch vụ của chúng tôi!</p>
                <a href="user/sanphamuser.php" class="btn">🛍️ Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <?php foreach ($grouped_orders as $group): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">Đơn hàng #<?php echo $group['donhang_id']; ?></div>
                        <div class="status"><?php echo $group['trang_thai']; ?></div>
                    </div>
                    
                    <div class="order-info">
                        <p><strong>📅 Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($group['ngay_tao'])); ?></p>
                        <p><strong>📦 Sản phẩm:</strong></p>
                        
                        <?php foreach ($group['items'] as $item): ?>
                            <?php 
                            $thanh_tien = floatval($item['don_gia']) * intval($item['so_luong']);
                            ?>
                            <div class="product-item">
                                <img src="<?php echo $item['anh_sanpham'] ?? 'no-image.png'; ?>" alt="<?php echo $item['ten_sanpham']; ?>">
                                <div class="product-info">
                                    <div class="product-name"><?php echo htmlspecialchars($item['ten_sanpham']); ?></div>
                                    <div class="product-qty">Số lượng: <?php echo $item['so_luong']; ?> x <?php echo number_format($item['don_gia'], 0, ',', '.'); ?>đ</div>
                                </div>
                                <div class="product-price">
                                    <?php echo number_format($thanh_tien, 0, ',', '.'); ?>đ
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-total">
                        💰 Tổng tiền: <?php echo number_format($group['tong_tien'], 0, ',', '.'); ?>đ
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>