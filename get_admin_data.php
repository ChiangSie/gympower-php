<?php
// 設置數據庫連接參數
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "g5";

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 設置字符集
$conn->set_charset("utf8mb4");

// SQL 查詢語句
$sql = "SELECT am_no, am_id, am_acc, am_psw, am_status FROM admin_table";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // 輸出每行數據
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// 關閉數據庫連接
$conn->close();

// 返回 JSON 格式的數據
header('Content-Type: application/json');
echo json_encode(['all' => $data]);
?>