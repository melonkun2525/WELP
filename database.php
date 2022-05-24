<?php

$db_name = "sns";
$db_user = "st";
$db_host = "localhost";
$db_password = "1234";

try{

    $mysql = new PDO(
        "mysql:host=" . $db_host . ";dbname=" . $db_name,
        $db_user,
        $db_password
    );

}catch(\PDOException $e){
    echo $e->getMessage();
    exit;
}

$stmt = $mysql->prepare("SELECT * FROM `follow`;");
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($res[0] as $key => $value){
    print("res[".$key."] = ".$value."<br>");
}

print("<br><br>");

foreach($res[1] as $key => $value){
    print("res[".$key."] = ".$value."<br>");
}