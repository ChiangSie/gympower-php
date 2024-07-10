<?php
//update_member_info
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    require_once("connect_cid101g5.php");
    $data = json_decode(file_get_contents("php://input"), true);

    $returnData = [
        'code' => 200,
        'msg' => '會員資料更新成功',
    ];

    $sql = "UPDATE member SET mem_name = :name, mem_acc = :acc, mem_phone = :phone";
    $params = [
        ':name' => $data['mem_name'],
        ':acc' => $data['mem_acc'],
        ':phone' => $data['mem_phone'],
        ':id' => $data['mem_id']
    ];

    // 如果提供了新密碼，則更新密碼
    if (!empty($data['mem_psw'])) {
        $sql .= ", mem_psw = :psw";
        $params[':psw'] = $data['mem_psw']; // 注意：實際應用中應該對密碼進行加密
    }

    $sql .= " WHERE mem_id = :id";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);

    if (!$result) {
        throw new Exception('更新失敗');
    }

} catch (Exception $e) {
    $returnData['code'] = 10003;
    $returnData['msg'] = $e->getMessage();
}

echo json_encode($returnData);
?>