<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/3/2016
 * Time: 7:15 PM
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

$jsonarray = array();

$str = "select * from APcurrentseason";
$result = $conn->query($str);

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $jsonarray[] = $row;
}

$str = "select ap.title,ap.frtitle,ap.season,ap.imgurl,ap.genre,ap.animetype,ap.description,ap.rating,ap.version,COALESCE (an.imgurl,'na') as annimgurl from APanimeinfo ap left outer join ANNanime an on ap.title=an.aptitle where ap.version>? order by version asc";
$stmt = $conn->prepare($str);
$stmt->bind_param("i",$clientVersion);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $jsonarray[] = $row;
    }
    $json = json_encode($jsonarray);
    //echo $json;
    echo AES_Encrypt($json,$AESkey);
}else{
    $row = array(
        'title' => 'Akagi',
        'frtitle' => 'Akagi',
        'season' => 'Fall 2005',
        'imgurl' => 'http://www.anime-planet.com/images/anime/covers/thumbs/akagi-1284.jpg',
        'genre' => 'Based on a Manga, Board Games, Gambling, High Stakes Games, Psychological, Seinen, Thriller, Mahjong',
        'animetype' => 'TV (26 eps)',
        'description' => 'One stormy night, a desperate man finds himself playing Mahjong with yakuza thugs; the prize is his life. He is losing, and death seems certain, until a teenage boy stumbles out of the darkness into the Mahjong parlor, drenched in rain. Allowed to watch, the boy soon offers to play in place of the marked man, and that night, a legend is born. After his first taste for Mahjong, Akagi Shigeru finds himself entangled in the dark underworld of Mahjong gambling: for money, reputation, and lives.',
        'rating' => 4,
        'version' => 0
    );
    $jsonarray[] = $row;
    $json = json_encode($jsonarray);
    //echo $json;
    echo AES_Encrypt($json,$AESkey);
}

?>