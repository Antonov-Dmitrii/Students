<?php
	session_start();
	require_once "lib/db.php";
		
	$user = DB::getObject();
	$auth = $user->isAuth();
	if(isset($_POST["auth"])) {
		$login = $_POST["login"];
		$password = $_POST["password"];
		$auth_success = $user->login($login, $password);
		if($auth_success){
			$_SESSION["isLogin"] = "true";
			$role = $user->getRole($login);
			$_SESSION["role"] = $role;

			$result_set = $user->getUserByLogin($login);
			$row = $result_set->fetch_assoc();
			$_SESSION["id"] = $row["id"];

			switch($role) {
				case "1": {header("Location: admin/home.php"); break;}
			}
			exit;
		}
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Авторизация</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body class="fonForBody">
	<div class="authPage">
		<form name="auth" action="" method="post">
			<h3>Вход для зарегистрированных пользователей</h3>
			<input class="standartInput" type="text" name="login" placeholder="Имя пользователя"> <br /><br />
			<input class="standartInput" type="password" name="password" placeholder="Пароль"> <br /><br />
			<input class="standartButton" type="submit" name="auth" value="ВОЙТИ">
		</form>
	</div>
		
	<div>
		<?php
			if($_POST["auth"]) {
				if(!$auth_success) echo "<h3 id=\"wrongAuth\" style='color: #8f1f1d; font-weight: bold; text-align: center; margin: 15px 10px 5px 10px'>Неверные имя пользователя и/или пароль</h3>";
			}
		?>
	</div>
</body>
</html>