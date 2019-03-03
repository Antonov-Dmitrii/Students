<?php
	if($_SESSION["isLogin"] != "true" || $_SESSION["role"] != 1) {
		header("Location: /notRightsEnought.php");
		exit;
	}
?>