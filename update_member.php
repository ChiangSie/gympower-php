<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json;charset=UTF-8');

require_once("./connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    // 取得資料
    $mem_id = $data["mem_id"];
    $mem_status = $data["mem_status"];

    // SQL 指令
    $sql = "UPDATE member SET  mem_status = :mem_status WHERE mem_id = :mem_id";

    // 編譯 SQL 指令
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mem_id', $mem_id);
    $stmt->bindValue(':mem_status', $mem_status);

    // 執行 SQL 指令
    $stmt->execute();

    $result = ["error" => false, "msg" => "教練資料更新成功"];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => "更新失敗：" . $e->getMessage()];
}

// 回傳資料給前端
echo json_encode($result);
?>