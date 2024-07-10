<?php
//get_member_once
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    require_once("connect_cid101g5.php");
    $data = json_decode(file_get_contents("php://input"), true);

    $returnData = [
        'code' => 200,
        'msg' => '',
        'data' => []
    ];

    $sql = "SELECT mem_name, mem_acc, mem_phone FROM member WHERE mem_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $data['id']);
    $stmt->execute();
    $memRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $returnData['data']['list'] = $memRows;
} catch (Exception $e) {
    $returnData['code'] = 10003;
    $returnData['msg'] = $e->getMessage();
}

echo json_encode($returnData);
?>