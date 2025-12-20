<?php
require_once "../config/database.php";

$success = "";
$error = "";

// Lấy id từ URL
if (!isset($_GET['id'])) {
    die("Không có id nhà cung cấp!");
}
$id = $_GET['id'];

// Lấy dữ liệu nhà cung cấp
$sql = "SELECT * FROM nhacungcap WHERE id = $id";
$data = $conn->query($sql)->fetch_assoc();

if (!$data) {
    die("Nhà cung cấp không tồn tại!");
}

// Khi nhấn nút cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ten = trim($_POST['ten']);
    $dia_chi = trim($_POST['dia_chi']);
    $email = trim($_POST['email']);
    $mo_ta = trim($_POST['mo_ta']);
    $so_dien_thoai = trim($_POST['so_dien_thoai']);

    // Kiểm tra
    if ($ten == "") {
        $error = "Vui lòng nhập tên nhà cung cấp.";
    } else {
        
        // Kiểm tra email trùng nhưng bỏ qua chính nó
        if ($email != "") {
            $check = $conn->query("SELECT * FROM nhacungcap WHERE email='$email' AND id <> $id");
            if ($check->num_rows > 0) {
                $error = "Email đã tồn tại!";
            }
        }

        // Nếu không lỗi → cập nhật
        if ($error == "") {
            $sqlUpdate = "UPDATE nhacungcap SET 
                            ten = '$ten',
                            dia_chi = '$dia_chi',
                            email = '$email',
                            mo_ta = '$mo_ta',
                            so_dien_thoai = '$so_dien_thoai'
                          WHERE id = $id";

            if ($conn->query($sqlUpdate)) {
        echo "<script>alert('Cập nhật thành công'); window.location='danhsachncc.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật');</script>";
    }
        }
    }
    header("Location: danhsachncc.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sửa nhà cung cấp</title>

<style>
    body {
        background: #f3f6f9;
        font-family: Arial, sans-serif;
    }

    .form-container {
        width: 850px;
        margin: 40px auto;
        background: white;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .field {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    input, textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
        outline: none;
        transition: .25s;
    }

    input:focus, textarea:focus {
        border-color: #00a86b;
        box-shadow: 0 0 4px rgba(0,168,107,0.6);
    }

    textarea {
        height: 80px;
        resize: none;
    }

    .btn_add {
        width: 220px;
        background: #00a86b;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: .25s;
        margin-top: 10px;
    }

    .btn_add:hover {
        background: #008f5b;
        transform: translateY(-2px);
    }

    .btn-wrap {
        text-align: center;
        margin-top: 10px;
    }

    .alert {
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 18px;
    }
</style>
</head>
<body>

<div class="form-container">
<form method="post">

    <h2>Sửa nhà cung cấp</h2>

    <!-- Thông báo -->
    <?php if ($success != ""): ?>
        <div class="alert" style="background:#d4f8e8; border-left:5px solid #00a86b;">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="alert" style="background:#ffe1e1; border-left:5px solid #ff4d4d;">
            <?= $error ?>
        </div>
    <?php endif; ?>


    <div class="row">
        <div class="field">
            <label>Tên nhà cung cấp:</label>
            <input type="text" name="ten" value="<?= $data['ten'] ?>">
        </div>

        <div class="field">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $data['email'] ?>">
        </div>
    </div>

    <div class="row">
        <div class="field">
            <label>Số điện thoại:</label>
            <input type="text" name="so_dien_thoai" value="<?= $data['so_dien_thoai'] ?>">
        </div>

        <div class="field">
            <label>Địa chỉ:</label>
            <input type="text" name="dia_chi" value="<?= $data['dia_chi'] ?>">
        </div>
    </div>

    <div class="row">
        <div class="field">
            <label>Mô tả:</label>
            <textarea name="mo_ta"><?= $data['mo_ta'] ?></textarea>
        </div>
    </div>

    <div class="btn-wrap">
        <button class="btn_add" type="submit">Cập nhật</button>
    </div>

</form>
</div>

</body>
</html>
