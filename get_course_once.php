<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // 包含資料庫連接設定
    require_once("connect_cid101g5.php");

    // 設定返回資料的初始值
    $returnData = [
        'code' => 200,
        'msg' => '',
        'data' => []
    ];

    // SQL查詢
    $sql = "SELECT  c_name as title, c_start as start, c_end as end FROM course as c1 join course_list as c2 on c1.c_id = c2.c_id join course_order as c3 on c3.o_id = c2.o_id where c3.o_mbid = 3 ";
    $courseords = $pdo->query($sql);
    $courseordRows = $courseords->fetchAll(PDO::FETCH_ASSOC);

    // 將查詢結果賦值給返回資料
    $returnData['data']['list'] = $courseordRows;
} catch (Exception $e) {
    // 捕獲異常並設置錯誤代碼和錯誤信息
    $returnData['code'] = 10003;
    $returnData['msg'] = $e->getMessage();
}

// 將返回資料編碼為JSON並輸出
echo json_encode($returnData);
?>