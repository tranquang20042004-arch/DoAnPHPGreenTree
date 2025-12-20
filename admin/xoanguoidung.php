<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID người dùng!");
}

$id = intval($_GET['id']); // chống injection

$sql = "UPDATE NguoiDung SET trang_thai = 0 WHERE id = $id";

excute($sql); // chạy câu SQL

echo "<script>
        alert('Xóa người dùng thành công!');
        window.location.href = 'danhsachnguoidung.php';// quay về trang danh sách
      </script>";
?>
