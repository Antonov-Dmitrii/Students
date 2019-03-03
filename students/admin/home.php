<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Домашняя страница</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../partsOfPages/menuForAdmin.php"; ?>

</body>
</html>