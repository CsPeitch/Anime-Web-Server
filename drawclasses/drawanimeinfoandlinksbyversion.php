<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 6/9/2016
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


/*
if(isset($_GET["version"])) {
    $clientVersion = $_GET["version"];
}else{
    die("version not set");
}*/

$str = "select Info.title,Info.imgurl,Info.genre,Info.episodes,Info.animetype,Info.agerating,Info.description,Allan.links as frlink,coalesce(Ulti.links,'na') as ultimalink,coalesce(M.link,'na') as MALlink,Info.version,coalesce(an.imgurl,'') as annimgurl from animeinfo Info inner join allanime Allan on Info.title=Allan.title left outer join animeultimanew Ulti on Info.title=Ulti.frtitle left outer join MALanime M on Info.title=M.frtitle left outer join ANNanime an on Info.title=an.frtitle where Info.version>? order by Info.version asc";
$stmt = $conn->prepare($str);
$stmt->bind_param("i",$clientVersion);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
    $jsonarray = array();
    while($row = $result->fetch_assoc()){
        $jsonarray[] = $row;
    }
    $json = json_encode($jsonarray);
    //echo $json;
    echo AES_Encrypt($json,$AESkey);
}else{
    $row = array(
        'title' => '009-1',
        'imgurl' => 'http://img.animefreak.tv/meta/11/19998.jpg',
        'genre' => 'Action, Mecha, Sci-Fi',
        'episodes' => '12',
        'animetype' => 'TV Series',
        'agerating' => 'Teen +17  ',
        'description' => 'Mylene Hoffman, a beautiful cyborg spy with the codename "009-1" lives in an alternative world where the cold war never ended, continuously on-going for 140 years. The world is split into two factions, the West and the East block. A masquerade of peace between the two is slowly dissipated as the conflict occurs. Through politics, the two factions battle over supremacy over technology to threats of a nuclear attack. Mylene Hoffman, teaming up with three other agent, gets surrounded by deception, chaos and rivalry as she carries out missions assigned by her superiors.',
        'frlink' => 'http://www.animefreak.tv/watch/009-1-online',
        'ultimalink' => 'http://www.animeultima.io/watch/009-1-english-subbed-dubbed-online/',
        'MALlink' => 'http://myanimelist.net/anime/1583/009-1',
        'version' => 0,
        'annimgurl' => 'http://cdn.animenewsnetwork.com/thumbnails/max500x600/encyc/A6862-5.jpg'
    );
    $jsonarray[] = $row;
    $json = json_encode($jsonarray);
    //echo $json;

    echo AES_Encrypt($json,$AESkey);
}

?>