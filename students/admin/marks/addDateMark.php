<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	$arrayOfLessonType = array("Обычное занятие", "Практика", "Курсовая работа", "Зачет", "Экзамен");
	
	if(isset($_GET["idGroup"])) {
		$idGroup = $_GET["idGroup"];
		$idSubject = $_GET["idSubject"];
	}
	
	if(isset($_POST["add"])) {
		$idGroup = $_POST["idGroup"];
		$idSubject = $_POST["idSubject"];
		$date = $_POST["date"];
		$type = $_POST["type"];
		
		$error_date = "";
		$error_name = "";
		$error = false;
		
		if(strlen($date) == 0) {
			$error_date = "Не заполнено поле";
			$error = true;
		}
		if($type == "Статус задания*") {
			$error_type = "Не заполнено поле";
			$error = true;
		}
		
		if(!$error) {			
			$db->addDateMark($idGroup, $idSubject, $date, $type);
					
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
		<h3>Создать занятие</h3>
		<p>Для создания занятия введите дату занятия и укажите вид занятия.</p>
		
		<form name="" action="" method="post">
			<div>
				<input class="standartInput" type="date" name="date" value="<?=$_POST["date"]?>"> <br />
				<span style="color: red";><?=$error_date ?></span>
			</div> <br />
			
			<div>
				<select class="standartSelect" name="type">
					<? if($type == "") { ?>
						<option selected="selected">Статус задания*</option>
					<?}
					else { ?>
						<option selected="selected"> <?=$type ?> </option>
					<?}?>
					<? for($i = 0; $i < count($arrayOfLessonType); $i++) {
						if($type != $arrayOfLessonType[$i]) { ?>
							<option><?=$arrayOfLessonType[$i] ?></option>
						<?}?>
					<?}?>
				</select> <br />
				<span style="color: red";><?=$error_type ?></span>
			</div> <br />
			
			<input type="hidden" name="idGroup" value="<?=$idGroup ?>">
			<input type="hidden" name="idSubject" value="<?=$idSubject ?>">
			<input class="standartButton" type="submit" name="add" value="Добавить">
		</form>
	</main>
</body>
</html>