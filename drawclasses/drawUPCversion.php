<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/22/2016
 * Time: 1:05 AM
 */
if(isset($_POST["sinfo"])){
    include "../config.php";
    include $pathToSeclib;

    $decodedstring = RSA_Decrypt($_POST["sinfo"], $pathToPrivKey);
    $jsonData = json_decode($decodedstring);
    $usr = $jsonData->usr;
    $pss = $jsonData->pss;
    $AESkey = $jsonData->key;

    if($usr!="gd4#DpxKli" || $pss!="pw2hT#S%g#"){
        die("");
    }

}else{
    die("");
}


$conn = mysqli_connect($servername,$username,$password,$db);
$conn->set_charset('utf8mb4');

if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}



$str = "select UPCversion from animedbversion";
$result = $conn->query($str);

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $json = json_encode($row);
    //echo $json;
    echo AES_Encrypt($json,$AESkey);
}

?>