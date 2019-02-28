<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/31/2016
 * Time: 6:35 PM
 */
if(isset($_POST["sinfo"])){
    include "../config.php";
    include $pathToSeclib;

    $decodedstring = RSA_Decrypt($_POST["sinfo"], $pathToPrivKey);
    $jsonData = json_decode($decodedstring);
    $usr = $jsonData->usr;
    $pss = $jsonData->pss;
    $AESkey = $jsonData->key;
    $clientVersion = $jsonData->vrs;

    if($usr!="gd4#DpxKli" || $pss!="pw2hT#S%g#"){
        die("");
    }

    if(!is_int($clientVersion)){
        die("");
    }
}else{
    die("");
}

$conn = mysqli_connect($servername,$username,$password,$db);
$conn->set_charset('utf8');

if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

$str = "select * from allanime where version>? order by version asc";
$stmt = $conn->prepare($str);
$stmt->bind_param("i",$clientVersion);
$stmt->execute();
//$result = $conn->query($str);

$result = $stmt->get_result();

if($result->num_rows > 0){
    $jsonarray = array();
    while($row = $result->fetch_assoc()){
        $jsonarray[] = $row;
    }
    $json = json_encode($jsonarray);
    //echo $json;
    echo AES_Encrypt($json,$AESkey);
}

?>