<?php

    class Encryption extends Database
    {
        public function getPublicKey()
        {
            return $this->select("SELECT PUBLIC_KEY FROM encryption")["PUBLIC_KEY"];
        }

        public function encrypt($data)
        {
            $publicKey = sodium_base642bin($this->getPublicKey(), SODIUM_BASE64_VARIANT_ORIGINAL);
            $encryptedData = sodium_crypto_box_seal($data, $publicKey);
            return sodium_bin2base64($encryptedData, SODIUM_BASE64_VARIANT_ORIGINAL);
        }

        public function decrypt($b64data)
        {
            $resultSet = $this->select("SELECT KEY_PAIR FROM encryption");
            $b64keypair = $resultSet[0]['KEY_PAIR'];

            $binkeypair = sodium_base642bin($b64keypair, SODIUM_BASE64_VARIANT_ORIGINAL);
            $binData = sodium_base642bin($b64data, SODIUM_BASE64_VARIANT_ORIGINAL);
            
            return sodium_crypto_box_seal_open($binData, $binkeypair);
        }
    }

?>