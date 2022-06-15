<?php

    class Encryption extends Database
    {
        public static function getPublicKey()
        {
            $resultSet = Database::select("SELECT PUBLIC_KEY FROM encryption");
            $b64PublicKey = $resultSet[0]["PUBLIC_KEY"];
            $binPublicKey = sodium_base642bin($b64PublicKey, SODIUM_BASE64_VARIANT_ORIGINAL);
            return $binPublicKey;
        }

        public static function encrypt($data)
        {
            $encryptedData = sodium_crypto_box_seal($data, Encryption::getPublicKey());
            return sodium_bin2base64($encryptedData, SODIUM_BASE64_VARIANT_ORIGINAL);
        }

        public static function decrypt($b64data)
        {
            $resultSet = Database::select("SELECT KEY_PAIR FROM encryption");
            $b64keypair = $resultSet[0]['KEY_PAIR'];

            $binkeypair = sodium_base642bin($b64keypair, SODIUM_BASE64_VARIANT_ORIGINAL);
            $binData = sodium_base642bin($b64data, SODIUM_BASE64_VARIANT_ORIGINAL);
            
            return sodium_crypto_box_seal_open($binData, $binkeypair);
        }
    }

?>