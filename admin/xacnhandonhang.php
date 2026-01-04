<?php
require "../config/database.php";

// Kiểm tra tham số
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: danhsachdonhang.php");
    exit();
}

$donhang_id = intval($_GET['id']);
$action = $_GET['action'];

// Xác định trạng thái mới dựa trên action
$trang_thai_moi = '';
$thong_bao = '';

switch($action) {
    case 'confirm':
        $trang_thai_moi = 'Đã xác nhận';
        $thong_bao = "✅ Đã xác nhận đơn hàng #$donhang_id";
        break;
    case 'shipping':
        $trang_thai_moi = 'Đang giao';
        $thong_bao = "🚚 Đơn hàng #$donhang_id đang được giao";
        break;
    case 'complete':
        $trang_thai_moi = 'Đã giao';
        $thong_bao = "✅ Đơn hàng #$donhang_id đã hoàn thành";
        break;
    case 'cancel':
        $trang_thai_moi = 'Đã hủy';
        $thong_bao = "❌ Đã hủy đơn hàng #$donhang_id";
        break;
    default:
        header("Location: danhsachdonhang.php");
        exit();
}

// Cập nhật trạng thái đơn hàng
$sql = "UPDATE donhang SET trang_thai = '$trang_thai_moi' WHERE id = $donhang_id";
$conn->query($sql);

// Chuyển hướng về trang danh sách với thông báo
echo "
<script>
    alert('$thong_bao');
    window.location.href = 'danhsachdonhang.php';
</script>
";
exit();
?>
