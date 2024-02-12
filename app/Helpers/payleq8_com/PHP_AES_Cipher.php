<?php

namespace App\Helpers\payleq8_com;

class PHP_AES_Cipher
{

    private static $OPENSSL_CIPHER_NAME = "aes-128-cbc"; //Name of OpenSSL Cipher
    private static $CIPHER_KEY_LEN = 16; //128 bits

    static function getKey($key) {
        if (strlen($key) < PHP_AES_Cipher::$CIPHER_KEY_LEN) {
            $key = str_pad("$key", PHP_AES_Cipher::$CIPHER_KEY_LEN, "0"); //0 pad to len 16
        } else if (strlen($key) > PHP_AES_Cipher::$CIPHER_KEY_LEN) {
            $key = substr($key, 0, PHP_AES_Cipher::$CIPHER_KEY_LEN); //truncate to 16 bytes
        }
        return $key;
    }

    static function encrypt($data , $key)
    {
        $key = self::getKey($key);
        $encryptedPayload = bin2hex(openssl_encrypt($data, PHP_AES_Cipher::$OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, $key));
        return strtoupper($encryptedPayload);
    }

    static function decrypt($data, $key)
    {
        $key = self::getKey($key);
        $hex2bin = hex2bin($data);
        $decryptedData = openssl_decrypt($hex2bin, PHP_AES_Cipher::$OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, $key);
        return $decryptedData;
    }
}
