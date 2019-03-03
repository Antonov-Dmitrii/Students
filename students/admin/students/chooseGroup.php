<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	if(isset($_GET["idBranch"])) {
		$idBranch = $_GET["idBranch"];
	}
	
	$result_set = $db->getRowWhereOrderNotDeleted("groups", "idBranch", $idBranch, "name");
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не была выбрана ни одна группа";
		else {
			header("Location: allStudents.php?idGroup=$idItem[0]");
			exit;
		}
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Список групп</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		<h3>Для работы со студентами выберите группу, в которой учится студент.</h3>
		<form name="" action="" method="post">
			<table class="" border='1'>
				<tr>
					<th></th>
					<th>Название группы</th>
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