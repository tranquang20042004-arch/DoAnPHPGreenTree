<?php
//require_once 'config.php';
require_once __DIR__ . '/config.php';

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

function excute($sql) {
    global $conn;
    if (!$conn->query($sql)) {
        echo "<b>LỖI SQL: </b>" . $conn->error . "<br><br>";
        echo "<b>SQL chạy:</b><br>" . nl2br($sql);
        exit;
    }
}

function excuteResult($sql) {
    global $conn;
    $result = $conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        echo "<b>LỖI SQL: </b>" . $conn->error . "<br>";
        echo "<b>SQL chạy:</b><br>" . nl2br($sql);
        exit;
    }
    return $data;
}
