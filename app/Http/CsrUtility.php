<?php

namespace App\Http;

class CsrUtility
{
    /**
     * Generate an Certificate Signing Request (csr)
     * 
     * @param  string $commonName
     * @return string $csrOut
     */
    public function generateCsr($commonName, $privateKeyType = 'OPENSSL_KEYTYPE_EC', $curveName = 'prime256v1', $digestAlg = 'sha384') {

        $subject = [
            'commonName' => $commonName
        ];

        $privateKey = openssl_pkey_new([
            'private_key_type' => $privateKeyType,
            'curve_name' => $curveName
        ]);

        $csr = openssl_csr_new($subject, $privateKey, [ 'digest_alg' => $digestAlg ]);

        openssl_csr_export($csr, $csrOut);

        return $csrOut;
    }
}