<?php
class Openssl
{
    private static $key = "1234567890abcdef1234567890abcdef"; // 32 caracteres
    private static $aes = "AES-256-ECB";

    public static function param_encrypt($data)
    {
        $encrypt = openssl_encrypt($data, self::$aes, self::$key);
        return urlencode(base64_encode($encrypt));
    }

    public static function param_decrypt($data)
    {
        $decoded = base64_decode(urldecode($data));
        return openssl_decrypt($decoded, self::$aes, self::$key);
    }
}
