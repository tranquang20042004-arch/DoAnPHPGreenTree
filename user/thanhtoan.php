<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra dữ liệu từ form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: giohangview.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_ids = isset($_POST['product_ids']) ? explode(',', $_POST['product_ids']) : [];
$ten_nguoinhan = $_POST['hoten'] ?? '';
$so_dien_thoai = $_POST['sdt'] ?? '';
$dia_chi_giao = $_POST['diachi'] ?? '';
$ghi_chu = $_POST['ghichu'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

// Kiểm tra thông tin bắt buộc
if (empty($ten_nguoinhan) || empty($so_dien_thoai) || empty($dia_chi_giao)) {
    $_SESSION['error'] = "⚠️ Vui lòng điền đầy đủ thông tin!";
    header("Location: giohangview.php");
    exit();
}

// Kiểm tra giỏ hàng
if (empty($_SESSION['cart']) || empty($product_ids)) {
    $_SESSION['error'] = "⚠️ Giỏ hàng trống!";
    header("Location: giohangview.php");
    exit();
}

// Tính tổng tiền từ các sản phẩm đã chọn
$tong_tien = 0;
$selected_products = [];

foreach ($product_ids as $id) {
    if (isset($_SESSION['cart'][$id])) {
        $item = $_SESSION['cart'][$id];
        $tong_tien += $item['gia'] * $item['qty'];
        $selected_products[$id] = $item;
    }
}

// Kiểm tra có sản phẩm được chọn không
if (empty($selected_products)) {
    $_SESSION['error'] = "⚠️ Không có sản phẩm nào được chọn!";
    header("Location: giohangview.php");
    exit();
}

// Lưu thông tin vào session để xử lý sau khi thanh toán VNPay
$_SESSION['checkout_info'] = [
    'product_ids' => $product_ids,
    'selected_products' => $selected_products,
    'ten_nguoinhan' => $ten_nguoinhan,
    'so_dien_thoai' => $so_dien_thoai,
    'dia_chi_giao' => $dia_chi_giao,
    'ghi_chu' => $ghi_chu,
    'tong_tien' => $tong_tien
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán VNPay - Green Tree</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .payment-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .payment-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .payment-body {
            padding: 40px;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .order-summary h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .customer-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .customer-info h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            width: 150px;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .qr-code-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .qr-code-section h3 {
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .qr-code-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .qr-image {
            max-width: 300px;
            width: 100%;
            height: auto;
            border: 3px solid #2e7d32;
            border-radius: 10px;
            padding: 10px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .qr-note {
            color: #666;
            font-size: 14px;
            font-style: italic;
        }

        .total-amount {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .payment-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            flex: 1;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-vnpay {
            background: #0d47a1;
            color: white;
        }

        .btn-vnpay:hover {
            background: #0a3a82;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #666;
        }

        .btn-cancel:hover {
            background: #e0e0e0;
        }

        .vnpay-logo {
            font-size: 24px;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>💳 Thanh toán VNPay</h1>
            <p>Xác nhận thông tin đơn hàng trước khi thanh toán</p>
        </div>

        <div class="payment-body">
            <!-- Thông tin khách hàng -->
            <div class="customer-info">
                <h3>📋 Thông tin người nhận</h3>
                <div class="info-row">
                    <div class="info-label">Họ và tên:</div>
                    <div class="info-value"><?php echo htmlspecialchars($ten_nguoinhan); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Số điện thoại:</div>
                    <div class="info-value"><?php echo htmlspecialchars($so_dien_thoai); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Địa chỉ:</div>
                    <div class="info-value"><?php echo htmlspecialchars($dia_chi_giao); ?></div>
                </div>
                <?php if (!empty($ghi_chu)): ?>
                <div class="info-row">
                    <div class="info-label">Ghi chú:</div>
                    <div class="info-value"><?php echo htmlspecialchars($ghi_chu); ?></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="order-summary">
                <h3>🛒 Sản phẩm đã chọn</h3>
                <?php foreach ($selected_products as $id => $item): ?>
                <div class="order-item">
                    <div>
                        <strong><?php echo htmlspecialchars($item['ten']); ?></strong>
                        <br>
                        <small>Số lượng: <?php echo $item['qty']; ?> × <?php echo number_format($item['gia'], 0, ',', '.'); ?>₫</small>
                    </div>
                    <div style="font-weight: bold; color: #2e7d32;">
                        <?php echo number_format($item['gia'] * $item['qty'], 0, ',', '.'); ?>₫
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Mã QR thanh toán -->
            <div class="qr-code-section">
                <h3>📱 Quét mã QR để thanh toán</h3>
                <div class="qr-code-container">
                    <img src="https://img.vietqr.io/image/MB-0866668888-compact2.png?amount=<?php echo $tong_tien; ?>&addInfo=GreenTree%20DonHang" 
                         alt="Mã QR thanh toán" 
                         class="qr-image">
                    <p class="qr-note">Quét mã QR bằng ứng dụng ngân hàng của bạn</p>
                </div>
            </div>

            <!-- Tổng tiền -->
            <div class="total-amount">
                <span>Tổng thanh toán:</span>
                <span><?php echo number_format($tong_tien, 0, ',', '.'); ?>₫</span>
            </div>

            <!-- Các nút thanh toán -->
            <div class="payment-buttons">
                <a href="giohangview.php" class="btn btn-cancel">⬅ Quay lại</a>
                <form action="xuly_vnpay.php" method="POST" style="flex: 1;">
                    <button type="submit" class="btn btn-vnpay" style="width: 100%;">
                        <span class="vnpay-logo">💳</span> Xác nhận thanh toán
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>