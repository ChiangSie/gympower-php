<?php
// add_admin.php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// 確保僅處理POST請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['code' => 405, 'msg' => 'Method Not Allowed']);
    exit();
}

// 讀取JSON輸入
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['acc']) || !isset($data['psw']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['code' => 400, 'msg' => 'Bad Request']);
    exit();
}

// 連接資料庫
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['code' => 500, 'msg' => 'Internal Server Error']);
    exit();
}

$id = $conn->real_escape_string($data['id']);
$acc = $conn->real_escape_string($data['acc']);
$psw = password_hash($conn->real_escape_string($data['psw']), PASSWORD_DEFAULT); // 密碼加密
$status = (int) $data['status'];

$sql = "INSERT INTO admin_table (am_id, am_acc, am_psw, am_status) VALUES ('$id', '$acc', '$psw', '$status')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['code' => 200, 'msg' => 'New record created successfully']);
} else {
    echo json_encode(['code' => 500, 'msg' => 'Error: ' . $sql . '<br>' . $conn->error]);
}

$conn->close();
?>
