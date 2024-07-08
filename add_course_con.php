<?php
// add_course_con.php

// 設置響應頭
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// 資料庫連接設定
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['code' => 500, 'msg' => '資料庫連接失敗：' . $e->getMessage()]));
}

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['code' => 400, 'msg' => '僅接受 POST 請求']));
}

// 獲取並驗證POST數據
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
$coach = filter_input(INPUT_POST, 'coach', FILTER_VALIDATE_INT);
$date = json_decode(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING), true);
$start_time = filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING);
$end_time = filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

// 驗證必填字段
if (!$title || !$content || !$coach || !$date || !$start_time || !$end_time || !$price) {
    die(json_encode(['code' => 400, 'msg' => '所有字段都是必填的']));
}

// 處理圖片上傳
$image_path = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $upload_dir = '/path/to/your/upload/directory/';
    $file_name = uniqid() . '_' . $_FILES['image']['name'];
    $upload_path = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
        $image_path = $file_name;
    } else {
        die(json_encode(['code' => 500, 'msg' => '圖片上傳失敗']));
    }
}

// 準備 SQL 語句
$sql = "INSERT INTO courses (title, content, coach_id, date, start_time, end_time, price, image, status) 
        VALUES (:title, :content, :coach, :date, :start_time, :end_time, :price, :image, :status)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':coach' => $coach,
        ':date' => json_encode($date),
        ':start_time' => $start_time,
        ':end_time' => $end_time,
        ':price' => $price,
        ':image' => $image_path,
        ':status' => $status
    ]);

    echo json_encode(['code' => 200, 'msg' => '課程新增成功']);
} catch (PDOException $e) {
    echo json_encode(['code' => 500, 'msg' => '資料庫錯誤：' . $e->getMessage()]);
}