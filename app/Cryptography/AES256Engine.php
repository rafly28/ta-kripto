<?php

namespace App\Cryptography;

class AES256Engine
{
    private $cipher = "AES-256-CBC";

    public function encrypt($plainText, $password)
    {
        $key = hash('sha256', $password, true);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $ciphertext = openssl_encrypt($plainText, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        return $iv . $ciphertext;
    }

    public function decrypt($combinedData, $password)
    {
        $key = hash('sha256', $password, true);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        
        $iv = substr($combinedData, 0, $ivLength);
        $ciphertext = substr($combinedData, $ivLength);

        return openssl_decrypt($ciphertext, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
    }
}