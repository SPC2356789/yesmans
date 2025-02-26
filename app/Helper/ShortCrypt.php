<?php

namespace App\Helper;

use Exception;

class ShortCrypt
{
    /**
     * 預設密鑰（32 字節）
     *
     * @var string|null
     */
    private static ?string $defaultKey = null;

    /**
     * 獲取或設置預設密鑰
     *
     * @return string
     */
    private static function getDefaultKey(): string
    {
        if (self::$defaultKey === null) {
            $rawKey = config('app.Privacy_key');
            self::$defaultKey = self::padKeyTo32Bytes($rawKey); // 修正為 32 字節
        }
        return self::$defaultKey;
    }

    /**
     * 加密資料並返回 Base64 字符串（不填充到 64 字元）
     *
     * @param string $data 要加密的資料
     * @param string|null $key 可選的自定義密鑰，預設使用類別的 $defaultKey
     * @param string $cipher 加密演算法（預設 AES-256-CBC）
     * @return string 加密後的 Base64 字符串
     * @throws Exception 如果加密失敗
     */
    public static function encrypt(string $data, ?string $key = null, string $cipher = 'AES-256-CBC'): string
    {
        $key = $key ?? self::getDefaultKey();

        if (strlen($key) !== 32) {
            throw new Exception('Key must be 32 bytes for AES-256');
        }
        if (strlen($data) > 32) {
            throw new Exception('Data must be less than 32 bytes');
        }

        $iv = $cipher === 'AES-256-CBC' ? random_bytes(16) : ''; // IV 修正為 16 字節
        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new Exception('Encryption failed');
        }

        $result = $cipher === 'AES-256-CBC' ? $iv . $encrypted : $encrypted;
        return base64_encode($result); // 直接返回 Base64，不填充
    }

    /**
     * 解密 Base64 加密字符串
     *
     * @param string $encrypted 加密後的 Base64 字符串
     * @param string|null $key 可選的自定義密鑰，預設使用類別的 $defaultKey
     * @param string $cipher 加密演算法（預設 AES-256-CBC）
     * @return string 解密後的原始資料
     * @throws Exception 如果解密失敗
     */
    public static function decrypt(string $encrypted, ?string $key = null, string $cipher = 'AES-256-CBC'): string
    {
        $key = $key ?? self::getDefaultKey();
        $decoded = base64_decode($encrypted);
        if ($decoded === false) {
            throw new Exception('Base64 decoding failed: ' . $encrypted);
        }
        if ($cipher === 'AES-256-CBC') {
            $iv = substr($decoded, 0, 16); // 提取前 16 字節作為 IV
            $data = substr($decoded, 16);  // 剩餘部分作為加密數據
            $decrypted = openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        } else {
            $decrypted = openssl_decrypt($decoded, $cipher, $key, OPENSSL_RAW_DATA);
        }
        if ($decrypted === false) {
            throw new Exception('Decryption failed: ' . openssl_error_string());
        }
        return $decrypted;
    }

    /**
     * 使用本身值循環補充密鑰到 32 字節
     *
     * @param string $key 原始密鑰
     * @return string 補充後的 32 字節密鑰
     */
    private static function padKeyTo32Bytes(string $key): string
    {
        $keyLength = strlen($key);

        if ($keyLength === 32) {
            return $key; // 如果已經是 32 字節，直接返回
        }

        if ($keyLength > 32) {
            return substr($key, 0, 32); // 如果超過 32 字節，截斷
        }

        // 小於 32 字節，循環補充
        $result = $key;
        while (strlen($result) < 32) {
            $remaining = 32 - strlen($result);
            $result .= substr($key, 0, min($keyLength, $remaining));
        }

        return $result;
    }
}

