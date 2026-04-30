<?php
// app/Services/DiffieHellmanService.php

namespace App\Services;

use phpseclib3\Math\BigInteger;
use phpseclib3\Crypt\DH;
use phpseclib3\Crypt\AES;

class DiffieHellmanService
{
    // Parameter DH standar (RFC 3526 - Group 14, 2048-bit)
    private string $prime = 'FFFFFFFFFFFFFFFFC90FDAA22168C234C4C6628B80DC1CD1' .
        '29024E088A67CC74020BBEA63B139B22514A08798E3404DD' .
        'EF9519B3CD3A431B302B0A6DF25F14374FE1356D6D51C245' .
        'E485B576625E7EC6F44C42E9A637ED6B0BFF5CB6F406B7ED' .
        'EE386BFB5A899FA5AE9F24117C4B1FE649286651ECE45B3D' .
        'C2007CB8A163BF0598DA48361C55D39A69163FA8FD24CF5F' .
        '83655D23DCA3AD961C62F356208552BB9ED529077096966D' .
        '670C354E4ABC9804F1746C08CA18217C32905E462E36CE3B' .
        'E39E772C180E86039B2783A2EC07A28FB5C55DF06F4C52C9' .
        'DE2BCBF6955817183995497CEA956AE515D2261898FA0510' .
        '15728E5A8AACAA68FFFFFFFFFFFFFFFF';
    private int $generator = 2;

    /**
     * Generate pasangan kunci DH untuk user
     */
    public function generateKeyPair(): array
    {
        $p = new BigInteger($this->prime, 16);
        $g = new BigInteger($this->generator);

        // Generate private key (random)
        $privateKey = new BigInteger(random_bytes(32), 256);

        // Hitung public key: g^privateKey mod p
        $publicKey = $g->modPow($privateKey, $p);

        return [
            'private_key' => $privateKey->toHex(),
            'public_key'  => $publicKey->toHex(),
        ];
    }

    /**
     * Hitung shared secret dari private key kita + public key lawan
     */
    public function computeSharedSecret(string $ourPrivateKeyHex, string $theirPublicKeyHex): string
    {
        $p = new BigInteger($this->prime, 16);

        $privateKey   = new BigInteger($ourPrivateKeyHex, 16);
        $theirPublicKey = new BigInteger($theirPublicKeyHex, 16);

        // Shared secret: theirPublicKey^ourPrivateKey mod p
        $sharedSecret = $theirPublicKey->modPow($privateKey, $p);

        // Derive AES key dengan SHA-256 (32 bytes = AES-256)
        return hash('sha256', $sharedSecret->toBytes(), true);
    }

    /**
     * Enkripsi pesan dengan AES-256-CBC
     */
    public function encrypt(string $message, string $aesKey): array
    {
        $iv = random_bytes(16); // 128-bit IV

        $aes = new AES('cbc');
        $aes->setKey($aesKey);
        $aes->setIV($iv);

        $encrypted = $aes->encrypt($message);

        return [
            'content' => base64_encode($encrypted),
            'iv'      => base64_encode($iv),
        ];
    }

    /**
     * Dekripsi pesan dengan AES-256-CBC
     */
    public function decrypt(string $encryptedContent, string $aesKey, string $iv): string
    {
        $aes = new AES('cbc');
        $aes->setKey($aesKey);
        $aes->setIV(base64_decode($iv));

        return $aes->decrypt(base64_decode($encryptedContent));
    }
}