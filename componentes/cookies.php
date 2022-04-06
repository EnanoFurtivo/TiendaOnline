<?php 

	function setCookie($nombre, $valor, $expDias)
	{
		setcookie($nombre, $valor, time() + ($expDias * 86400 * 30), "/");
	}

	function getCookie($nombre)
	{
		return (isset($_COOKIE[$nombre])) ? $_COOKIE[$nombre] : null;;
	}

?>