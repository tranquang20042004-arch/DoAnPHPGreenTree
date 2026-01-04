<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

// Kiểm tra ID đơn hàng
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "⚠️ Không tìm thấy đơn hàng!";
    header("Location: donhang.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$donhang_id = intval($_GET['id']);

// Kiểm tra đơn hàng có thuộc về user này không
$sql = "SELECT * FROM donhang WHERE id = $donhang_id AND nguoidung_id = $user_id";
$order = excuteResult($sql);

if (empty($order)) {
    $_SESSION['error'] = "⚠️ Không tìm thấy đơn hàng hoặc bạn không có quyền hủy đơn hàng này!";
    header("Location: donhang.php");
    exit();
}

$order = $order[0];
$trang_thai = $order['trang_thai'];

// Chỉ cho phép hủy đơn hàng đang ở trạng thái "Chờ xác nhận"
if ($trang_thai !== 'Chờ xác nhận') {
    $_SESSION['error'] = "⚠️ Chỉ có thể hủy đơn hàng đang chờ xác nhận!";
    header("Location: donhang.php");
    exit();
}

// Cập nhật trạng thái đơn hàng thành "Đã hủy"
$sql_update = "UPDATE donhang SET trang_thai = 'Đã hủy' WHERE id = $donhang_id";
excute($sql_update);

$_SESSION['success'] = "✅ Hủy đơn hàng #$donhang_id thành công!";
header("Location: donhang.php");
exit();
?>
