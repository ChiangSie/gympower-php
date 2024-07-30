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

if (!isset($data["u_account"]) || !isset($data["u_psw"])) {
    echo json_encode(['code' => 0, 'msg' => '帳號或密碼未提供']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT am_id, am_acc, am_psw, am_status FROM admin WHERE am_acc = :uAccount AND am_psw = :uPsw");
    $stmt->bindValue(":uAccount", $data["u_account"]);
    $stmt->bindValue(":uPsw", $data["u_psw"]);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin) {
        if ($admin['am_status'] == 0) {
            echo json_encode(['code' => 0, 'msg' => '此帳號已被停權']);
        } else {
            unset($admin['am_psw']); // Remove password for security
            echo json_encode(['code' => 1, 'adminInfo' => $admin]);
        }
    } else {
        echo json_encode(['code' => 0, 'msg' => '帳號未找到或密碼錯誤']);
    }
} catch (PDOException $e) {
    echo json_encode(['code' => 0, 'msg' => '資料庫錯誤: ' . $e->getMessage()]);
}
?>
