<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include("connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);
error_log("Received data: " . json_encode($data));

if (!isset($data["u_account"]) || !isset($data["u_psw"])) {
    echo json_encode(['code' => 0, 'msg' => '帳號或密碼未提供']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT mem_id, mem_acc, mem_psw, mem_status FROM member WHERE mem_acc = :uAccount");
    $stmt->bindValue(":uAccount", $data["u_account"]);
    $stmt->execute();

    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("Fetched member data: " . json_encode($member));

    if ($member) {
        // 暫時移除密碼驗證，直接比較明文密碼
        if ($data["u_psw"] === $member['mem_psw']) {
            if ($member['mem_status'] == 0) {
                echo json_encode(['code' => 0, 'msg' => '此帳號已被停權']);
            } else {
                unset($member['mem_psw']); // Remove password for security
                echo json_encode(['code' => 1, 'memInfo' => $member]);
            }
        } else {
            echo json_encode(['code' => 0, 'msg' => '密碼錯誤']);
        }
    } else {
        echo json_encode(['code' => 0, 'msg' => '帳號未找到']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['code' => 0, 'msg' => '資料庫錯誤，請稍後再試']);
}
?>