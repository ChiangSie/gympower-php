<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json;charset=UTF-8');

require_once("./connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    $c_id = $data["c_id"];
    $c_price = $data["c_price"];
    $c_content = $data["c_content"];
    $c_status = $data["c_status"];

    $sql = "UPDATE course SET c_status = :c_status, c_content = :c_content, c_price = :c_price, c_status = :c_status  WHERE c_id = :c_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':c_id', $c_id);
    $stmt->bindValue(':c_status', $c_status);
    $stmt->bindValue(':c_price', $c_price);
    $stmt->bindValue(':c_content', $c_content);

    $stmt->execute();

    $result = ["code" => 200, "msg" => "報告狀態更新成功"];
} catch (PDOException $e) {
    $result = ["code" => 500, "msg" => "資料庫錯誤：" . $e->getMessage()];
} catch (Exception $e) {
    $result = ["code" => 500, "msg" => "更新失敗：" . $e->getMessage()];
}

echo json_encode($result);
?>