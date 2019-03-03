<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
		
	$db = DB::getObject();
	
	if(isset($_GET["idMark"])) {
		$idMark = $_GET["idMark"];
		$idSubject = $_GET["idSubject"];
		$idGroup = $_GET["idGroup"];
	
		$result_set = $db->getRowWhere("marks", "id", $idMark);
		$row = $result_set->fetch_assoc();
	}
	
	if(isset($_POST["edit"])) {		
		$idMark = $_POST["idMark"];
		$idSubject = $_POST["idSubject"];
		$idGroup = $_POST["idGroup"];

		$mark = $_POST["mark"];
		
		$error_mark = "";
		$error = false;
		
		if($mark != "" & $mark != 2 & $mark != 3 & $mark != 4 & $mark != 5) {
			$error_mark = "Оценка может быть только 2, 3, 4 или 5 баллов";
			$error = true;
		}
		
		if(!$error) {			
			$db->editMark($idMark, $mark);
			
			header("Location: showSchedule.php?idGroup=$idGroup&idSubject=$idSubject");
			exit;
		}
	}
	
	if(isset($_POST["close"])) {
		$idSubject = $_POST["idSubject"];
		$idGroup = $_POST["idGroup"];
		
		header("Location: showSchedule.php?idGroup=$idGroup&idSubject=$idSubject");
		exit;
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Редактировать оценку</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	
	<style>
		.b-popup{
			width:100%;
			height: 2000px;
			background-color: rgba(0,0,0,0.5);
			overflow:hidden;
			position:fixed;
			top:0px;
		}
		.b-popup .b-popup-content{
			margin:40px auto 0px auto;
			width:300px;
			height: auto;
			padding:10px;
			background-color: #c5c5c5;
			border-radius:5px;
			box-shadow: 0px 0px 10px #000;
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="b-popup" id="popup1">
		<div class="b-popup-content">
			<h3>Изменить или удалить оценку</h3>
			<p>Для удаления оценки оставьте поле пустым.</p>
			<form name="" class="" action="" method="post">
				<div>
					<input class="smallInput" type="text" name="mark" placeholder="Оценка*" value="<?=isset($_POST["mark"])? $_POST["mark"]:$row[mark]?>"> <br />
					<span style="color: red";><?=$error_mark ?></span>
				</div> <br />

				<input type="hidden" name="idMark" value="<?=$idMark ?>">
				<input type="hidden" name="idSubject" value="<?=$idSubject ?>">
				<input type="hidden" name="idGroup" value="<?=$idGroup ?>">
				<input class="standartButton" type="submit" name="edit" value="Изменить">
				<input class="standartButton" id="buttonPopup" type="submit" name="close" value="Закрыть">
			</form>
		</div>
	</div>
</body>
</html>