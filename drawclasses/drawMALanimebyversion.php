<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/1/2016
 * Time: 1:11 PM
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

$str = "select M.title,M.link,M.version from MALanime M inner join animeinfo A on M.frtitle=A.title where M.version>? order by M.version asc";
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