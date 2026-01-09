<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra có ID sản phẩm không
if (isset($_POST['id'])) {
    $product_id = $_POST['id'];
    
    // Kiểm tra sản phẩm có tồn tại trong giỏ hàng không
    if (isset($_SESSION['cart'][$product_id])) {
        // Xóa sản phẩm khỏi giỏ hàng
        unset($_SESSION['cart'][$product_id]);
        
        // Nếu giỏ hàng rỗng, xóa luôn session cart
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        
        // Thông báo thành công
        $_SESSION['message'] = "✅ Đã xóa sản phẩm khỏi giỏ hàng!";
        $_SESSION['message_type'] = "success";
    } else {
        // Sản phẩm không tồn tại trong giỏ hàng
        $_SESSION['message'] = "⚠️ Sản phẩm không tồn tại trong giỏ hàng!";
        $_SESSION['message_type'] = "error";
    }
} else {
    // Không có ID sản phẩm
    $_SESSION['message'] = "⚠️ Có lỗi xảy ra!";
    $_SESSION['message_type'] = "error";
}

// Quay lại trang giỏ hàng
header("Location: giohangview.php");
exit();
?>
