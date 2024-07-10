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
    $mem_name = $data["addname"];
    $mem_acc = $data["addacc"];
    $mem_email = $data["addemail"];
    $mem_phone = $data["addphone"];
    $mem_psw = $data["addpsw"];
    $mem_status = 1; // Default status for new members
    $mem_img = null; // Photo field is null by default

    // Check if account already exists
    $checkSql = "SELECT COUNT(*) FROM member WHERE mem_acc = :mem_acc";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':mem_acc' => $mem_acc]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        $result = ["error" => true, "msg" => "帳號名稱已被使用"];
    } else {
        // Get the maximum mem_id value
        $maxMemIdSql = "SELECT MAX(mem_id) AS max_mem_id FROM member";
        $maxMemIdStmt = $pdo->query($maxMemIdSql);
        $maxMemId = $maxMemIdStmt->fetchColumn() + 1;

        // Insert new member record
        $sql = "INSERT INTO members (mem_id, mem_name, mem_acc, mem_email, mem_phone, mem_psw, mem_status, mem_img) 
                VALUES (:mem_id, :mem_name, :mem_acc, :mem_email, :mem_phone, :mem_psw, :mem_status, :mem_img)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":mem_id", $maxMemId);
        $stmt->bindValue(":mem_name", $mem_name);
        $stmt->bindValue(":mem_acc", $mem_acc);
        $stmt->bindValue(":mem_email", $mem_email);
        $stmt->bindValue(":mem_phone", $mem_phone);
        $stmt->bindValue(":mem_psw", password_hash($mem_psw, PASSWORD_DEFAULT)); // Hash the password
        $stmt->bindValue(":mem_status", $mem_status);
        $stmt->bindValue(":mem_img", $mem_img, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            $result = ["error" => false, "msg" => "註冊成功"];
        } else {
            $result = ["error" => true, "msg" => "註冊失敗：資料庫錯誤"];
        }
    }
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => "註冊失敗：" . $e->getMessage()];
}

// Return result as JSON
echo json_encode($result);
?>