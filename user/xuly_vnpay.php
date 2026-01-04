<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

// Kiểm tra thông tin checkout
if (!isset($_SESSION['checkout_info'])) {
    $_SESSION['error'] = "⚠️ Không tìm thấy thông tin đơn hàng!";
    header("Location: giohangview.php");
    exit();
}

$checkout_info = $_SESSION['checkout_info'];
$user_id = $_SESSION['user_id'];
$product_ids = $checkout_info['product_ids'];
$selected_products = $checkout_info['selected_products'];
$ten_nguoinhan = $checkout_info['ten_nguoinhan'];
$so_dien_thoai = $checkout_info['so_dien_thoai'];
$dia_chi_giao = $checkout_info['dia_chi_giao'];
$ghi_chu = $checkout_info['ghi_chu'];
$tong_tien = $checkout_info['tong_tien'];
$phuong_thuc_tt = 'VNPay - Thanh toán trực tuyến';

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
    
    // Xóa thông tin checkout
    unset($_SESSION['checkout_info']);
    
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
