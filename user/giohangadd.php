<?php
session_start();
require "../config/database.php";

$id  = (int)$_POST['id'];
$qty = (int)$_POST['qty'];

// Lấy thông tin sản phẩm
$sql = "SELECT sp.id, sp.ten, sp.gia, ha.url AS anh
        FROM SanPham sp
        LEFT JOIN HinhAnh ha ON ha.sanpham_id = sp.id
        WHERE sp.id = $id LIMIT 1";
$result  = $conn->query($sql);
$product = $result->fetch_assoc();

if ($product) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu sản phẩm đã có trong giỏ thì cộng thêm số lượng
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'ten' => $product['ten'],
            'gia' => $product['gia'],
            'anh' => $product['anh'],
            'qty' => $qty
        ];
    }

    // Báo thành công rồi chuyển sang giỏ hàng
    echo "<script>alert('Thêm vào giỏ hàng thành công!'); window.location='giohangview.php';</script>";
} else {
    // Báo thất bại
    echo "<script>alert('Thêm vào giỏ hàng thất bại!'); window.history.back();</script>";
}
?>