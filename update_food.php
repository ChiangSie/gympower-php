<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json;charset=UTF-8');

require_once("./connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    // 取得資料
    $fd_id = $data["fd_id"];
    // $fd_img = $data["fd_img"];
    $fd_name = $data["fd_name"];
    $fd_fat = $data["fd_fat"];
    $fd_sugar = $data["fd_sugar"];
    $fd_protein = $data["fd_protein"];
    $fd_cal = $data["fd_cal"];
    $fd_price = $data["fd_price"];
    $fd_status = $data["fd_status"];

    // SQL 指令
    $sql = "UPDATE food SET  fd_name = :fd_name, fd_fat = :fd_fat, fd_sugar = :fd_sugar, fd_protein = :fd_protein, fd_price = :fd_price, fd_cal = :fd_cal, fd_status = :fd_status WHERE fd_id = :fd_id";

    // 編譯 SQL 指令
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':fd_id', $fd_id);
    // $stmt->bindValue(':fd_img', $fd_img);
    $stmt->bindValue(':fd_name', $fd_name);
    $stmt->bindValue(':fd_cal', $fd_cal);
    $stmt->bindValue(':fd_fat', $fd_fat);
    $stmt->bindValue(':fd_sugar', $fd_sugar);
    $stmt->bindValue(':fd_protein', $fd_protein);
    $stmt->bindValue(':fd_price', $fd_price);
    $stmt->bindValue(':fd_status', $fd_status);

    // 執行 SQL 指令
    $stmt->execute();

    $result = ["code" => 200, "msg" => "食物資料更新成功"];
} catch (PDOException $e) {
    $result = ["code" => 500, "msg" => "資料庫錯誤：" . $e->getMessage()];
} catch (Exception $e) {
    $result = ["code" => 500, "msg" => "更新失敗：" . $e->getMessage()];
}

// 回傳資料給前端
echo json_encode($result);
?>