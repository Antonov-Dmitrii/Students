<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	$result_set = $db->getAllNotDeletedOrder("branches", "name");
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не было выбрано ни одно отделение";
		else {
			header("Location: showGroups.php?branch=$idItem[0]");
			exit;
		}
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Список предметов</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		<h3>Для работы с группой выберите отделение, в которое входит данная группа.</h3>
		<form name="" action="" method="post">
			<table class="" border='1'>
				<tr>
					<th></th>
					<th>Название отделения</th>
				</tr>
				<? while (($row = $result_set->fetch_assoc()) != false) { ?>					
					<tr>
						<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
						<td><?=$row[name] ?></td>
					</tr>
				<?}?>
			</table> <br />
			<input class="standartButton pr" type="submit" name="editMarkedItem" value="Выбрать"> <br /><br />
			<span style="color: red";><?=$error_delete ?></span>
		</form>
	</main>
</body>
</html>