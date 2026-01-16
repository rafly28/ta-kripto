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
        
        if ($ciphertext === false) {
            throw new \Exception("Encryption failed: " . openssl_error_string());
        }
        
        // Generate HMAC untuk integrity check
        $hmacKey = hash('sha256', $password . 'hmac', true);
        $hmac = hash_hmac('sha256', $iv . $ciphertext, $hmacKey, true);
        
        return $hmac . $iv . $ciphertext;
    }

    public function decrypt($combinedData, $password)
    {
        $hmacLength = 32; // SHA-256 = 32 bytes
        $key = hash('sha256', $password, true);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        
        if (strlen($combinedData) < ($hmacLength + $ivLength)) {
            return false;
        }
        
        // Extract HMAC, IV, dan ciphertext
        $hmac = substr($combinedData, 0, $hmacLength);
        $iv = substr($combinedData, $hmacLength, $ivLength);
        $ciphertext = substr($combinedData, $hmacLength + $ivLength);
        
        // Verify HMAC
        $hmacKey = hash('sha256', $password . 'hmac', true);
        $expectedHmac = hash_hmac('sha256', $iv . $ciphertext, $hmacKey, true);
        
        if (!hash_equals($hmac, $expectedHmac)) {
            return false; // Data tampered atau password salah
        }
        
        $plaintext = openssl_decrypt($ciphertext, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        if ($plaintext === false) {
            return false;
        }
        
        return $plaintext;
    }
}