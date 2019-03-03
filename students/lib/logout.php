<?php
	session_start();
	unset($_SESSION["login"]);
	unset($_SESSION["isLogin"]);
	unset($_SESSION["password"]);
	header("Location: ../index.php");
	exit;
?>