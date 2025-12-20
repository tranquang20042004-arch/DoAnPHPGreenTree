<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tai_khoan = $_POST['tai_khoan'];
    $email = $_POST['email'];
    $ho = $_POST['ho'];
    $ten = $_POST['ten'];
    $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
    $vaitro_id = $_POST['vaitro_id'];
    $sdt = $_POST['so_dien_thoai'];
    $dia_chi = $_POST['dia_chi'];

    $sql = "INSERT INTO NguoiDung (tai_khoan, email, ho, ten, mat_khau, vaitro_id, so_dien_thoai, dia_chi)
            VALUES ('$tai_khoan', '$email', '$ho', '$ten', '$mat_khau', '$vaitro_id', '$sdt', '$dia_chi')";
    excute($sql);

    header("Location: danhsachnguoidung.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm người dùng</title>

    <style>
        body {
            background: #f3f6f9;
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 420px;
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

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            transition: 0.25s;
        }

        input:focus, select:focus {
            border-color: #00a86b;
            box-shadow: 0 0 4px rgba(0,168,107,0.6);
        }

        label {
            font-weight: 600;
            color: #333;
        }

        .btn_add {
            width: 100%;
            background: #00a86b;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.25s;
            box-shadow: 0px 3px 7px rgba(0, 0, 0, 0.15);
        }

        .btn_add:hover {
            background: #008f5b;
            transform: translateY(-2px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn_add:active {
            transform: scale(0.95);
        }
    </style>

</head>
<body>

<div class="form-container">
<form method="post">
    <h2>Thêm người dùng</h2> 
    
    <label>Họ:</label>
    <input type="text" name="ho">

    <br><br>

    <label>Tên:</label>
    <input type="text" name="ten">
   
    <br><br>

    <label>Tài khoản:</label>
    <input type="text" name="tai_khoan">

    <br><br>

    <label>Email:</label>
    <input type="email" name="email">

    <br><br>

    

    <label>Mật khẩu:</label>
    <input type="password" name="mat_khau">

    <br><br>

    <label>Vai trò:</label>
    <select name="vaitro_id">
        <option value="1">Admin</option>
        <option value="2">User</option>
    </select>

    <br><br>

    <label>Số điện thoại:</label>
    <input type="text" name="so_dien_thoai">

    <br><br>

    <label>Địa chỉ:</label>
    <input type="text" name="dia_chi">

    <br><br>

    <button class="btn_add" type="submit">Thêm</button>
</form>
</div>

</body>
</html>

