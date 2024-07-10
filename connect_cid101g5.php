<?php 
    //本地
	$dbname = "g5";
	$user = "root";
	$password = "";


	//部屬
    // $dbname = "tibamefe_cid101g5";
    // $user = "tibamefe_since2021";
    // $password = "vwRBSb.j&K#E";
    // $port = 3306;





	$dsn = "mysql:host=localhost;port=3306;dbname=$dbname;charest=utf8";;
	$options = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_CASE=>PDO::CASE_NATURAL];

	//建立pdo物件
	$pdo = new PDO($dsn, $user, $password, $options);
?>