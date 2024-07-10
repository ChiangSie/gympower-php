<?php
//validate_password
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
        'isValid' => false
    ];

    $sql = "SELECT mem_psw FROM member WHERE mem_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $data['mem_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // 假設密碼在數據庫中是以明文存儲的
        // 如果是加密存儲的，您需要使用適當的解密或驗證方法
        $returnData['isValid'] = ($result['mem_psw'] === $data['mem_psw']);
    }

    if (!$returnData['isValid']) {
        $returnData['msg'] = '密碼驗證失敗';
    }

} catch (Exception $e) {
    $returnData['code'] = 10003;
    $returnData['msg'] = $e->getMessage();
}

echo json_encode($returnData);
?>