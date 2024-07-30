<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json;charset=UTF-8');

require_once("./connect_cid101g5.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    // 取得資料
    $coach_id = $data["coach_id"];
    $coach_img = $data["coach_img"];
    $tag = $data["tag"];
    $intro = $data["intro"];
    $coach_rcm = $data["coach_rcm"];

    // SQL 指令
    $sql = "UPDATE coach SET coach_img = :coach_img, tag = :tag, intro = :intro, coach_rcm = :coach_rcm WHERE coach_id = :coach_id";

    // 編譯 SQL 指令
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':coach_id', $coach_id);
    $stmt->bindValue(':coach_img', $coach_img);
    $stmt->bindValue(':tag', $tag);
    $stmt->bindValue(':intro', $intro);
    $stmt->bindValue(':coach_rcm', $coach_rcm);

    // 執行 SQL 指令
    $stmt->execute();

    $result = ["error" => false, "msg" => "教練資料更新成功"];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => "更新失敗：" . $e->getMessage()];
}

// 回傳資料給前端
echo json_encode($result);
?>