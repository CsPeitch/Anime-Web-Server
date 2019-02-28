<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/4/2017
 * Time: 11:37 PM
 */
function AES_Encrypt($plaintext, $key)
{
    include('phpseclib/Crypt/AES.php');
    include('phpseclib/Crypt/Random.php');

    $cipher = new Crypt_AES(CRYPT_AES_MODE_ECB);
    $cipher->disablePadding();

    $decodedkey = base64_decode($key);
    $cipher->setKey($decodedkey);

    $ciphered_text = $cipher->encrypt($plaintext);


    $output = base64_encode($ciphered_text);

    return $output;
}

function RSA_Decrypt($ciphertext, $pathToKey)
{
    include('phpseclib/Crypt/RSA.php');

    $rsa = new Crypt_RSA();

    $temp = base64_decode($ciphertext);

    $rsa->loadKey(file_get_contents($pathToKey,true));
    $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_OAEP);
    $rsa->setHash('sha256');
    return $rsa->decrypt($temp);
}

?>