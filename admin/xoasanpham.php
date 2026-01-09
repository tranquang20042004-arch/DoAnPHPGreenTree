<?php
require "../config/database.php";

$id = $_GET['id'] ?? null;
if (!$id) die("Không xác định sản phẩm");

// Xóa ảnh trước
$conn->query("DELETE FROM hinhanh WHERE sanpham_id=$id");

// Xóa sản phẩm
if($conn->query("DELETE FROM sanpham WHERE id=$id")){
    echo "<script>alert('Xóa sản phẩm thành công!'); window.location='danhsachsanpham.php';</script>";
}else{
    echo "<script>alert('Lỗi: ".$conn->error."'); window.location='danhsachsanpham.php';</script>";
}
?>
