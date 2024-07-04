<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json;charset=UTF-8');

require_once("./connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    $r_id = $data["r_id"];
    $r_status = $data["r_status"];

    $sql = "UPDATE report SET r_status = :r_status WHERE r_id = :r_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':r_id', $r_id);
    $stmt->bindValue(':r_status', $r_status);

    $stmt->execute();

    $result = ["code" => 200, "msg" => "報告狀態更新成功"];
} catch (PDOException $e) {
    $result = ["code" => 500, "msg" => "資料庫錯誤：" . $e->getMessage()];
} catch (Exception $e) {
    $result = ["code" => 500, "msg" => "更新失敗：" . $e->getMessage()];
}

echo json_encode($result);
?>