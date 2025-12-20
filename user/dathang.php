<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

// Ngăn cache trang
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$error = '';
$success = '';

// Lấy danh sách ID sản phẩm được chọn
$selected_ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];

if (empty($selected_ids)) {
    header("Location: giohangview.php");
    exit();
}

// Lấy giỏ hàng từ session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$selected_items = [];
$tong_tien = 0;

foreach ($selected_ids as $id) {
    if (isset($cart[$id])) {
        $selected_items[$id] = $cart[$id];
        $tong_tien += floatval($cart[$id]['gia']) * intval($cart[$id]['qty']);
    }
}

if (empty($selected_items)) {
    header("Location: giohangview.php");
    exit();
}

if (isset($_POST['dat_hang'])) {
    $ten_nguoi_nhan = trim($_POST['ten_nguoi_nhan']);
    $sdt = trim($_POST['sdt']);
    $dia_chi = trim($_POST['dia_chi']);
    $phuong_thuc_tt = isset($_POST['phuong_thuc_tt']) ? $_POST['phuong_thuc_tt'] : 'COD';
    
    if (empty($ten_nguoi_nhan) || empty($sdt) || empty($dia_chi)) {
        $error = "Vui lòng điền đầy đủ thông tin giao hàng!";
    } else {
        $user_id = $_SESSION['user_id'];
        $ngay_tao = date('Y-m-d H:i:s');
        $trang_thai = 'Chờ xác nhận';
        
        // Thêm đơn hàng
        $sql = "INSERT INTO donhang (nguoidung_id, ten_nguoinhan, so_dien_thoai, dia_chi_giao, 
                tong_tien, phuong_thuc_tt, trang_thai, ngay_tao, thanh_tien, hoadon_id) 
                VALUES ($user_id, '$ten_nguoi_nhan', '$sdt', '$dia_chi', 
                '$tong_tien', '$phuong_thuc_tt', '$trang_thai', '$ngay_tao', '$tong_tien', 0)";
        
        excute($sql);
        
        // Lấy ID đơn hàng vừa tạo
        $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
        $donhang_id = mysqli_insert_id($conn);
        mysqli_close($conn);
        
        // Thêm chi tiết đơn hàng
        foreach ($selected_items as $item) {
            $sanpham_id = $item['id'];
            $so_luong = $item['qty'];
            $don_gia = $item['gia'];
            
            $sql = "INSERT INTO donhang_chitiet (donhang_id, sanpham_id, so_luong, don_gia) 
                    VALUES ($donhang_id, $sanpham_id, $so_luong, '$don_gia')";
            excute($sql);
        }
        
        // Xóa các sản phẩm đã mua khỏi giỏ hàng
        foreach ($selected_ids as $id) {
            unset($_SESSION['cart'][$id]);
        }
        
        $success = "✅ Đặt hàng thành công! Mã đơn hàng: #$donhang_id";
        
        // Chuyển hướng sau 2 giây
        header("refresh:2;url=../text.php");
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng - Green Tree</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f5;
        }
        
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .checkout-form {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group select {
            cursor: pointer;
        }
        
        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .order-summary h3 {
            color: #2e7d32;
            margin-top: 0;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-item:last-child {
            border-bottom: none;
        }
        
        .total {
            font-size: 20px;
            font-weight: 700;
            color: #2e7d32;
            border-top: 2px solid #ddd;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .btn-primary {
            background: #2e7d32;
            color: #fff;
            width: 100%;
        }
        
        .btn-primary:hover {
            background: #1b5e20;
            transform: translateY(-2px);
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
            display: inline-block;
            text-decoration: none;
            margin-top: 15px;
        }

        .btn-back:hover {
            background: #5a6268;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .required {
            color: #dc3545;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-option:hover {
            border-color: #2e7d32;
            background: #f8f9fa;
        }

        .payment-option input[type="radio"] {
            margin-right: 10px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .payment-option label {
            cursor: pointer;
            margin: 0;
            font-weight: 500;
        }

        .payment-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        h1 {
            color: #2e7d32;
            margin-bottom: 30px;
        }

        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 22px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Đặt hàng</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="checkout-form">
            <h2>📋 Thông tin giao hàng</h2>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="ten_nguoi_nhan">Họ tên người nhận <span class="required">*</span></label>
                    <input type="text" id="ten_nguoi_nhan" name="ten_nguoi_nhan" required 
                           placeholder="Nhập họ và tên"
                           value="<?php echo $_SESSION['ho_ten'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="sdt">Số điện thoại <span class="required">*</span></label>
                    <input type="tel" id="sdt" name="sdt" required 
                           pattern="[0-9]{10,11}" 
                           placeholder="Ví dụ: 0345530628">
                </div>
                
                <div class="form-group">
                    <label for="dia_chi">Địa chỉ giao hàng <span class="required">*</span></label>
                    <textarea id="dia_chi" name="dia_chi" required 
                              placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Phương thức thanh toán <span class="required">*</span></label>
                    
                    <div class="payment-option">
                        <input type="radio" id="cod" name="phuong_thuc_tt" value="Thanh toán khi nhận hàng" checked>
                        <span class="payment-icon">💵</span>
                        <label for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    
                    <div class="payment-option">
                        <input type="radio" id="bank" name="phuong_thuc_tt" value="Chuyển khoản ngân hàng">
                        <span class="payment-icon">🏦</span>
                        <label for="bank">Chuyển khoản ngân hàng</label>
                    </div>
                </div>
                
                <h2>📦 Chi tiết đơn hàng</h2>
                
                <div class="order-summary">
                    <h3>Sản phẩm đã chọn:</h3>
                    <?php foreach ($selected_items as $item): ?>
                        <div class="summary-item">
                            <span>
                                <strong><?php echo htmlspecialchars($item['ten']); ?></strong><br>
                                <small style="color: #666;">Số lượng: <?php echo $item['qty']; ?></small>
                            </span>
                            <span style="font-weight: 600;"><?php echo number_format($item['gia'] * $item['qty'], 0, ',', '.'); ?>đ</span>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="summary-item total">
                        <span>💰 Tổng cộng:</span>
                        <span><?php echo number_format($tong_tien, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>
                
                <button type="submit" name="dat_hang" class="btn btn-primary">
                    ✅ Xác nhận đặt hàng
                </button>

                <a href="giohangview.php" class="btn btn-back">
                    ← Quay lại giỏ hàng
                </a>
            </form>
        </div>
    </div>
</body>
</html>
