<?php 

	function set_cookie($nombre, $valor, $expDias)
	{
		setcookie($nombre, $valor, time() + ($expDias * 86400 * 30), "/");
	}

	function get_cookie($nombre)
	{
		return (isset($_COOKIE[$nombre])) ? $_COOKIE[$nombre] : null;;
	}

	function delete_cookie($cookie)
	{
		setcookie($cookie, "", time() - 3600);
	}
	
?>