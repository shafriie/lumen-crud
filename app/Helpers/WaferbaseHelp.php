<?php

namespace App\Helpers;

class WaferbaseHelp
{
	public function encryptGlobal($data, $key)
    {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public function decryptGlobal($data, $key)
    {
        try {
            $encryption_key = base64_decode($key);
            list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
            $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
            return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
