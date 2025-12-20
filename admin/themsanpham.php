<?php
require "../config/database.php";

// Lấy danh sách danh mục và nhà cung cấp
$danhmuc = $conn->query("SELECT * FROM DanhMuc");
$nhacungcap = $conn->query("SELECT * FROM NhaCungCap");

// Xử lý khi submit form
if (isset($_POST['them'])) {
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $danhmuc_id = $_POST['danhmuc_id'];
    $nhacungcap_id = $_POST['nhacungcap_id'];
    $so_luong = $_POST['so_luong'];
    $url = $_POST['url']; // link ảnh
    $mo_ta = $_POST['mo_ta'];

    $sql = "INSERT INTO SanPham (ten, gia, danhmuc_id, nhacungcap_id, so_luong, ngay_tao, ngay_capnhat, mo_ta) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiss", $ten, $gia, $danhmuc_id, $nhacungcap_id, $so_luong, $mo_ta);

    if ($stmt->execute()) {
        // Lấy ID sản phẩm vừa thêm
        $sp_id = $stmt->insert_id;
        // Thêm URL ảnh vào bảng HinhAnh
        $conn->query("INSERT INTO HinhAnh (sanpham_id, url) VALUES ($sp_id, '$url')");
        echo "<script>alert('Thêm sản phẩm thành công!'); window.location='danhsachsanpham.php';</script>";
    } else {
        echo "<script>alert('Lỗi: ".$conn->error."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm sản phẩm mới</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f3f4f6;
        padding: 20px;
    }
    .form-container {
        width: 500px;
        margin: auto;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #2c3e50;
    }
    label {
        font-weight: bold;
    }
    input, select, textarea, button {
        width: 100%;
        margin-top: 5px;
        margin-bottom: 15px;
        padding: 10px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    button {
        background: #4CAF50;
        color: white;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background: #43a047;
    }
</style>
</head>
<body>

<div class="form-container">
    <h2>Thêm sản phẩm mới</h2>
    <form method="post">
        <label>Tên sản phẩm:</label>
        <input type="text" name="ten" placeholder="Nhập tên sản phẩm" required>

        <label>Giá (VNĐ):</label>
        <input type="number" name="gia" placeholder="Nhập giá" required>

        <label>Danh mục:</label>
        <select name="danhmuc_id" required>
            <?php while($dm = $danhmuc->fetch_assoc()): ?>
                <option value="<?= $dm['id'] ?>"><?= $dm['ten'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Nhà cung cấp:</label>
        <select name="nhacungcap_id" required>
            <?php while($ncc = $nhacungcap->fetch_assoc()): ?>
                <option value="<?= $ncc['id'] ?>"><?= $ncc['ten'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Số lượng:</label>
        <input type="number" name="so_luong" placeholder="Nhập số lượng" required>

        <label>URL ảnh sản phẩm:</label>
        <input type="text" name="url" placeholder="Nhập link hình sản phẩm" required>

        <label>Mô tả:</label>
        <textarea name="mo_ta" rows="4" placeholder="Nhập mô tả sản phẩm"></textarea>

        <button type="submit" name="them">Thêm sản phẩm</button>
    </form>
</div>

</body>
</html>
