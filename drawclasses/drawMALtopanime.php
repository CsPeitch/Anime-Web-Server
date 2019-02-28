<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/1/2016
 * Time: 1:09 PM
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
$conn->set_charset('utf8mb4');

if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

$str = "select A.title,M.score from MALtopanime M inner join MALanime MA on M.title=MA.title inner join animeinfo A on MA.frtitle=A.title order by M.score desc";
$result = $conn->query($str);

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