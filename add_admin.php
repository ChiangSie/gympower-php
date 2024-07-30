<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json;charset=UTF-8");

// Database connection
require_once("connect_cid101g5.php");

// Retrieve POST data
$data = json_decode(file_get_contents("php://input"), true);

try {
    // Extract data
    $am_id = $data["id"];
    $am_acc = $data["acc"];
    $am_psw = $data["psw"];
    $am_status = isset($data["status"]) ? $data["status"] : 1; // Default status

    // Check if account already exists
    $checkSql = "SELECT COUNT(*) FROM admin WHERE am_acc = :am_acc";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':am_acc' => $am_acc]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        $result = ["error" => true, "msg" => "帳號名稱已被使用"];
    } else {
        // Get the maximum am_no value
        $maxAmNoSql = "SELECT MAX(am_no) AS max_am_no FROM admin";
        $maxAmNoStmt = $pdo->query($maxAmNoSql);
        $maxAmNo = $maxAmNoStmt->fetchColumn() + 1;

        // Insert new admin record
        $sql = "INSERT INTO admin (am_no, am_id, am_acc, am_psw, am_status) VALUES (:am_no, :am_id, :am_acc, :am_psw, :am_status)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":am_no", $maxAmNo);
        $stmt->bindValue(":am_id", $am_id);
        $stmt->bindValue(":am_acc", $am_acc);
        $stmt->bindValue(":am_psw", $am_psw);
        $stmt->bindValue(":am_status", $am_status);
        $stmt->execute();

        $result = ["error" => false, "msg" => "新增成功"];
    }
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => "新增失敗：" . $e->getMessage()];
}

// Return result as JSON
echo json_encode($result);
?>