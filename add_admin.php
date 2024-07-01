<?php
header('Content-Type: application/json');

// 資料庫連接設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "g5";

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "連接失敗: " . $conn->connect_error]));
}

// 獲取POST數據
$data = json_decode(file_get_contents('php://input'), true);

// 驗證數據
if (empty($data['id']) || empty($data['acc']) || empty($data['psw'])) {
    echo json_encode(['success' => false, 'message' => '所有欄位都必須填寫']);
    exit;
}

// 準備SQL語句
$stmt = $conn->prepare("INSERT INTO admin_table (am_id, am_acc, am_psw, am_status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $data['id'], $data['acc'], $data['psw'], $data['status']);

// 執行SQL
if ($stmt->execute()) {
    $newAdmin = [
        'am_no' => $stmt->insert_id,
        'am_id' => $data['id'],
        'am_acc' => $data['acc'],
        'am_psw' => $data['psw'],
        'am_status' => $data['status']
    ];
    echo json_encode(['success' => true, 'admin' => $newAdmin]);
} else {
    echo json_encode(['success' => false, 'message' => '新增失敗: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>