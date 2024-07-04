<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json;charset=UTF-8');

require_once("./connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    // 取得資料
    $am_no = $data["am_no"];
    $am_status = $data["am_status"];

    // SQL 指令
    $sql = "UPDATE admin SET  am_status = :am_status WHERE am_no = :am_no";

    // 編譯 SQL 指令
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':am_no', $am_no);
    $stmt->bindValue(':am_status', $am_status);

    // 執行 SQL 指令
    $stmt->execute();

    $result = ["error" => false, "msg" => "教練資料更新成功"];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => "更新失敗：" . $e->getMessage()];
}

// 回傳資料給前端
echo json_encode($result);
?>