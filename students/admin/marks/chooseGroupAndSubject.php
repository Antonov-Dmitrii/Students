<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	$result_set =  $db->getAllNotDeletedOrder("groups", "name");
	$result_set1 = $db->getAllNotDeletedOrder("subjects", "name");
	
	if(isset($_POST["editMarkedItem"])) {
		$idGroup = $_POST["idGroup"];
		$idSubject = $_POST["idSubject"];

		$error_idGroup = "";
		$error_idSubject = "";
		$error = false;
		
		if($idGroup == "Выберите группу*") {
			$error_idGroup = "Не сделан выбор";
			$error = true;
		}
		if($idSubject == "Выберите предмет*") {
			$error_idSubject = "Не сделан выбор";
			$error = true;
		}
		
		if(!$error) {
			header("Location: showSchedule.php?idGroup=$idGroup&idSubject=$idSubject");
			exit;
		}
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Работа с журналом</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		<h3>Для отображения страницы журнала выберите группу и предмет.</h3>
		<form name="" action="" method="post">			
			<div>
				<select class="standartSelect" name="idGroup">
					<? if($idGroup == "" | $idGroup == "Выберите группу*") { ?>
						<option selected="selected">Выберите группу*</option>
					<?}
					else {
						$result_set2 = $db->getRowWhere("groups", "id", $_POST["idGroup"]);
						$row2 = $result_set2->fetch_assoc(); ?>
						<option selected="selected" value="<?=$_POST["idGroup"] ?>"><?=$row2["name"] ?></option>
					<?}?>
					<? while (($row = $result_set->fetch_assoc()) != false) { ?>
						<? if($row2["name"] != $row[name]) {?>
							<option value="<?=$row[id] ?>"><?=$row[name] ?></option>
						<?}?>
					<?}?>
				</select> <br />
				<span style="color: red";><?=$error_idGroup ?></span>
			</div> <br />

			<div>
				<select class="standartSelect" name="idSubject">
					<? if($idSubject == "" | $idSubject == "Выберите предмет*") { ?>
						<option selected="selected">Выберите предмет*</option>
					<?}
					else {
						$result_set3 = $db->getRowWhere("subjects", "id", $_POST["idSubject"]);
						$row3 = $result_set3->fetch_assoc(); ?>
						<option selected="selected" value="<?=$_POST["idSubject"] ?>"><?=$row3["name"] ?></option>
					<?}?>
					<? while (($row1 = $result_set1->fetch_assoc()) != false) { ?>
						<? if($row3["name"] != $row1[name]) {?>
							<option value="<?=$row1[id] ?>"><?=$row1[name] ?></option>
						<?}?>
					<?}?>
				</select> <br />
				<span style="color: red";><?=$error_idSubject ?></span>
			</div> <br />

			<input class="standartButton pr" type="submit" name="editMarkedItem" value="Выбрать"> <br /><br />
			<span style="color: red";><?=$error_delete ?></span>
		</form>
	</main>
</body>
</html>