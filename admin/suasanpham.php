<?php
require "../config/database.php";

$id = $_GET['id'] ?? null;
if (!$id) die("Không xác định sản phẩm");

// Lấy dữ liệu sản phẩm
$sp = $conn->query("SELECT sp.*, ha.url AS anh FROM sanpham sp LEFT JOIN hinhanh ha ON sp.id = ha.sanpham_id WHERE sp.id=$id")->fetch_assoc();
$danhmuc = $conn->query("SELECT * FROM danhmuc");
$nhacungcap = $conn->query("SELECT * FROM nhacungcap");

if (isset($_POST['capnhat'])) {
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $danhmuc_id = $_POST['danhmuc_id'];
    $nhacungcap_id = $_POST['nhacungcap_id'];
    $so_luong = $_POST['so_luong'];
    $url = $_POST['url'];
    $mo_ta = $_POST['mo_ta'];

    $sql = "UPDATE sanpham SET ten=?, gia=?, danhmuc_id=?, nhacungcap_id=?, so_luong=?, mo_ta=?, ngay_capnhat=NOW() WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiissi", $ten, $gia, $danhmuc_id, $nhacungcap_id, $so_luong, $mo_ta, $id);

    if ($stmt->execute()) {
        $conn->query("UPDATE hinhanh SET url='$url' WHERE sanpham_id=$id");
        echo "<script>alert('Cập nhật sản phẩm thành công!'); window.location='danhsachsanpham.php';</script>";
    } else {
        echo "<script>alert('Lỗi: ".$conn->error."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa sản phẩm</title>
<style>
     body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 0;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-top: 30px;
    }
    form {
        max-width: 500px;
        margin: 30px auto;
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    label {
        font-weight: bold;
        color: #555;
        display: block;
        margin-bottom: 6px;
    }
    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    input:focus, select:focus, textarea:focus {
        border-color: #007bff;
        outline: none;
    }
    textarea {
        min-height: 80px;
        resize: vertical;
    }
    button {
        background: #038d26ff;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }
    button:hover {
        background: #0056b3;
    }

</style>
</head>
<body>

<form method="post"><h2>Sửa sản phẩm</h2>
    <label>Tên sản phẩm:</label><br>
    <input type="text" name="ten" value="<?= $sp['ten'] ?>" required><br>

    <label>Giá:</label><br>
    <input type="number" name="gia" value="<?= $sp['gia'] ?>" required><br>

    <label>Danh mục:</label><br>
    <select name="danhmuc_id" required>
        <?php while($dm = $danhmuc->fetch_assoc()): ?>
            <option value="<?= $dm['id'] ?>" <?= $dm['id']==$sp['danhmuc_id']?'selected':'' ?>><?= $dm['ten'] ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Nhà cung cấp:</label><br>
    <select name="nhacungcap_id" required>
        <?php while($ncc = $nhacungcap->fetch_assoc()): ?>
            <option value="<?= $ncc['id'] ?>" <?= $ncc['id']==$sp['nhacungcap_id']?'selected':'' ?>><?= $ncc['ten'] ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Số lượng:</label><br>
    <input type="number" name="so_luong" value="<?= $sp['so_luong'] ?>" required><br>

    <label>URL ảnh:</label><br>
    <input type="text" name="url" value="<?= $sp['anh'] ?>" required><br>

    <label>Mô tả:</label><br>
    <textarea name="mo_ta"><?= $sp['mo_ta'] ?></textarea><br><br>

    <button type="submit" name="capnhat">Cập nhật</button>
</form>
</body>
</html>
