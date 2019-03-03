<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	$success = 0;
	if(!isset($_SESSION["success"])) {
		$success = 0;
	}
	else $success = $_SESSION["success"];
	
	if(isset($_POST["editMarkedItem"])) {
		$idGroup = $_POST["idGroup"];
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один студент";
		else {
			header("Location: editStudent.php?edit=$idItem[0]&idGroup=$idGroup");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idGroup = $_POST["idGroup"];
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один студент";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idGroup = $_POST["idGroup"];
		$idItem = $_POST["idItem"];
		foreach($idItem as $value) {
			$db->doIsDeleted("students", $value);
		}
		
		$_SESSION["success"] = 1;
		
		header("Location: allStudents.php?idGroup=$idGroup");
		exit;
	}

	if(isset($_POST["cancelDelete"])) {
		$addConfirmButton = false;
	}
	
	if(isset($_GET["idGroup"])) {
		$idGroup = $_GET["idGroup"];
		
		$result_set = $db->getRowWhere("groups", "id", $idGroup);
		$row = $result_set->fetch_assoc();
		
		$result_set1 = $db->getRowWhereOrderNotDeleted("students", "idGroup", $idGroup, "name");
?>		
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Список студентов, учащихся в группе "<?=$row[name]?>"</title>
			<link rel="stylesheet" type="text/css" href="/css/style.css" />
		</head>
		<body>
			<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
			<main class="main ">
			
				<? if($success == 1) {?>
					<p class="success">Данные успешно сохранены!</p>
				<?}?>
				
				<? if($addConfirmButton) {?>
					<form name="comfirmDelete" action="" method="post">
						<p><b>Вы действительно хотите удалить следующих студентов?</b></p>
						<?
							$i = 0;
							foreach($idItem as $value) {
								$result_set4 = $db->getRowWhere("students", "id", $value);
								$row4 = $result_set4->fetch_assoc(); ?>
								<input type="hidden" name="idItem[]" value="<?=$idItem[$i]?>">
								<? echo "<b>".$row4[name]."</b>"."<br />";
								$i++;
							}
						?> <br />
						<input type="hidden" name="idGroup" value="<?=$idGroup ?>">
						<input class="standartButton" type="submit" name="comfirmDelete" value="Удалить">
						<input class="standartButton" type="submit" name="cancelDelete" value="Отменить">
					</form>
					<br /> 
				<?}?>
				
				<a href="addStudent.php?idGroup=<?=$idGroup?>">Добавить студента</a> <br />
				
				<div class="">
					<h3>Список студентов, учащихся в группе "<?=$row[name]?>"</h3>
					<form name="" action="" method="post">
						<input class="standartButton pr" type="submit" name="editMarkedItem" value="Правки">
						<input class="standartButton de" type="submit" name="deleteMarkedItems" value="Удалить">
						<span style="color: red";><?=$error_delete ?></span> <br /> <br />
						<input type="hidden" name="idGroup" value="<?=$idGroup ?>">
						
						<table class="" border='1'>
							<tr>
								<th></th>
								<th>ФИО</th>
								<th>Дата рождения</th>
								<th>Документ, удостов.личность</th>
								<th>Примечания</th>
							</tr>
							<? while (($row1 = $result_set1->fetch_assoc()) != false) { ?>		
								<? $date = date('d.m.Y', $row1["date"]); ?>
								<tr>
									<td><input type="checkbox" name="idItem[]" value="<?=$row1[id] ?>"></td>
									<td><?=$row1[name] ?></td>
									<td><?=$date ?></td>
									<td><?=$row1[docs] ?></td>
									<td><?=$row1[note] ?></td>
								</tr>
							<?}?>
						</table>
					</form> <br />
				</div>
			</main>
		</body>
		</html>
	<?}?>
	<?	$_SESSION["success"] = 0; ?>