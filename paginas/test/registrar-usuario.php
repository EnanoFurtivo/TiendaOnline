<?php

	$dir = dirname( __FILE__, 3 );
	require_once($dir.'/componentes/db.php');
	require_once($dir.'/modelo/encryption.php');
	require_once($dir.'/vendor/paragonie/sodium_compat/autoload.php');
	
    if (!isset($_POST["username"]) || !isset($_POST["password"]))

	$query = "SELECT PUBLIC_KEY FROM encryption";
	$publicKey = Encryption::getPublicKey();

	$username = $_POST["username"];
	$password = $_POST["password"];

	$passwordHash = hash('sha256', $password);
	echo "username: ".$username." hash: ".$passwordHash." encrypted: ";

	$encryptedBox = sodium_crypto_box_seal($passwordHash, $publicKey);
	$encrypted = sodium_bin2base64($encryptedBox, SODIUM_BASE64_VARIANT_ORIGINAL);

	echo $encrypted;

    Database::insert("INSERT INTO usuario(USERNAME, PASSWORD) VALUES (?,?)", [$username,$encrypted]);

?>