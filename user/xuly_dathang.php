<?php
session_start();
require_once '../config/database.php';

// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra phương thức thanh toán
if (!isset($_POST['payment_method']) || !isset($_POST['product_ids'])) {
    header("Location: giohangview.php");
    exit();
}

$payment_method = $_POST['payment_method'];

// Nếu chọn VNPay, chuyển sang trang thanh toán
if ($payment_method === 'vnpay') {
    // Chuyển hướng sang trang thanh toán VNPay
    header("Location: thanhtoan.php");
    exit();
}

// XỬ LÝ THANH TOÁN COD
$user_id = $_SESSION['user_id'];
$product_ids = explode(',', $_POST['product_ids']);
$ten_nguoinhan = $_POST['hoten'] ?? '';
$so_dien_thoai = $_POST['sdt'] ?? '';
$dia_chi_giao = $_POST['diachi'] ?? '';
$ghi_chu = $_POST['ghichu'] ?? '';
$phuong_thuc_tt = 'COD - Thanh toán khi nhận hàng';

// Kiểm tra thông tin bắt buộc
if (empty($ten_nguoinhan) || empty($so_dien_thoai) || empty($dia_chi_giao)) {
    $_SESSION['error'] = "⚠️ Vui lòng điền đầy đủ thông tin!";
    header("Location: giohangview.php");
    exit();
}

// Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
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

// Escape dữ liệu
$ten_nguoinhan = $conn->real_escape_string($ten_nguoinhan);
$so_dien_thoai = $conn->real_escape_string($so_dien_thoai);
$dia_chi_giao = $conn->real_escape_string($dia_chi_giao);
$ghi_chu = $conn->real_escape_string($ghi_chu);

// BẮT ĐẦU TRANSACTION
$conn->begin_transaction();

try {
    // 1. Thêm đơn hàng vào bảng donhang với trạng thái "Chờ xác nhận"
    $sql_donhang = "INSERT INTO donhang (nguoidung_id, ten_nguoinhan, so_dien_thoai, dia_chi_giao, tong_tien, thanh_tien, phuong_thuc_tt, trang_thai, ngay_tao, hoadon_id) 
                    VALUES ($user_id, '$ten_nguoinhan', '$so_dien_thoai', '$dia_chi_giao', '$tong_tien', '$tong_tien', '$phuong_thuc_tt', 'Chờ xác nhận', NOW(), 0)";
    
    if (!$conn->query($sql_donhang)) {
        throw new Exception("Lỗi thêm đơn hàng: " . $conn->error);
    }
    $donhang_id = $conn->insert_id;
    
    // 2. Thêm chi tiết đơn hàng vào bảng donhang_chitiet
    foreach ($selected_products as $id => $item) {
        $so_luong = intval($item['qty']);
        $don_gia = $item['gia'];
        
        $sql_chitiet = "INSERT INTO donhang_chitiet (donhang_id, sanpham_id, so_luong, don_gia) 
                        VALUES ($donhang_id, $id, $so_luong, '$don_gia')";
        
        if (!$conn->query($sql_chitiet)) {
            throw new Exception("Lỗi thêm chi tiết đơn hàng: " . $conn->error);
        }
        
        // 3. Xóa sản phẩm đã đặt khỏi giỏ hàng
        unset($_SESSION['cart'][$id]);
    }
    
    // Nếu giỏ hàng rỗng, xóa session cart
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
    
    // COMMIT TRANSACTION
    $conn->commit();
    
    // Thông báo thành công
    $_SESSION['success'] = "✅ Đặt hàng thành công! Đơn hàng #$donhang_id đang chờ xác nhận từ Admin.";
    header("Location: donhang.php");
    exit();
    
} catch (Exception $e) {
    // ROLLBACK nếu có lỗi
    $conn->rollback();
    
    $_SESSION['error'] = "⚠️ Có lỗi xảy ra: " . $e->getMessage();
    header("Location: giohangview.php");
    exit();
}
?>
