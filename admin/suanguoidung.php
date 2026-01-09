<?php
require '../config/database.php';

$id = $_GET["id"];

// Lấy dữ liệu người dùng theo ID
$sql = "SELECT * FROM nguoidung WHERE id = $id";
$result = $conn->query($sql);
$u = $result->fetch_assoc();

// Khi bấm nút cập nhật
if (isset($_POST["capnhat"])) {
    $ho = $_POST["ho"];
    $ten = $_POST["ten"];
    $email = $_POST["email"];
    $sdt = $_POST["so_dien_thoai"];
    $vaitro = $_POST["vaitro"];

    $sqlUpdate = "UPDATE nguoidung SET
                    ho='$ho',
                    ten='$ten',
                    email='$email',
                    so_dien_thoai='$sdt',
                    vaitro_id='$vaitro'
                  WHERE id=$id";

    if ($conn->query($sqlUpdate)) {
        echo "<script>alert('Cập nhật thành công'); window.location='danhsachnguoidung.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật');</script>";
    }
}
?>

<style>
    .edit-form-container {
        width: 450px;
        margin: 20px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    .edit-form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #2c3e50;
        font-size: 22px;
    }

    .edit-form-container label {
        font-weight: bold;
        color: #34495e;
    }

    .edit-form-container input,
    .edit-form-container select {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        transition: 0.2s;
    }

    .edit-form-container input:focus,
    .edit-form-container select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.4);
    }

    .edit-form-container button {
        width: 100%;
        background: #4CAF50;
        border: none;
        color: white;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }

    .edit-form-container button:hover {
        background: #43a047;
    }
</style>


<div class="edit-form-container">
    <h2>Sửa người dùng</h2>

    <form method="post">
        <label>Họ:</label>
        <input type="text" name="ho" value="<?php echo $u['ho']; ?>">

        <label>Tên:</label>
        <input type="text" name="ten" value="<?php echo $u['ten']; ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $u['email']; ?>">

        <label>Số điện thoại:</label>
        <input type="text" name="so_dien_thoai" value="<?php echo $u['so_dien_thoai']; ?>">

        <label>Vai trò:</label>
        <select name="vaitro">
            <option value="1" <?php if($u['vaitro_id']==1) echo "selected"; ?>>Admin</option>
            <option value="2" <?php if($u['vaitro_id']==2) echo "selected"; ?>>User</option>
        </select>

        <button type="submit" name="capnhat">Cập nhật</button>
    </form>
</div>
