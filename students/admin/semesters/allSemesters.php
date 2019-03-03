<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$success = 0;
	if(!isset($_SESSION["success"])) {
		$success = 0;
	}
	else $success = $_SESSION["success"];
	
	$db = DB::getObject();
	$result_set = $db->getAllNotDeletedOrder("start_and_end_semesters_dates", "startDate DESC");
	
	if(isset($_POST["editMarkedItem"])) {
		$idItem = $_POST["idItem"];

		if(!isset($idItem))
			$error_delete = "Не был выбран ни один семестр";
		else {
			header("Location: editSemester.php?edit=$idItem[0]");
			exit;
		}
	}
	else if(isset($_POST["deleteMarkedItems"])) {
		$idItem = $_POST["idItem"];
		if(!isset($idItem))
			$error_delete = "Не был выбран ни один семестр";
		else
			$addConfirmButton = true;
	}
	
	if(isset($_POST["comfirmDelete"])) {
		$idItem = $_POST["idItem"];
		foreach($idItem as $value) {
			$db->doIsDeleted("start_and_end_semesters_dates", $value);
		}
		
		$_SESSION["success"] = 1;
		
		header("Location: allSemesters.php");
		exit;
	}

	if(isset($_POST["cancelDelete"])) {
		$addConfirmButton = false;
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Список всех семестров</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
	<?php require_once "../../partsOfPages/menuForAdmin.php"; ?>
	<main class="main">
		
		<? if($success == 1) {?>
			<p class="success">Данные успешно сохранены!</p>
		<?}?>
		
		<? if($addConfirmButton) {?>
			<form name="comfirmDelete" action="" method="post">
				<p><b>Вы действительно хотите удалить следующие семестры?</b></p>
				<?
					$i = 0;
					foreach($idItem as $value) {
						$result_set4 = $db->getRowWhere("start_and_end_semesters_dates", "id", $value);
						$row4 = $result_set4->fetch_assoc(); ?>
						<input type="hidden" name="idItem[]" value="<?=$idItem[$i]?>">
						<? echo "<b>".date('d.m.Y', $row4["startDate"])." - ".date('d.m.Y', $row4["endDate"])."</b>"."<br />";
						$i++;
					}
				?> <br />
				<input class="standartButton" type="submit" name="comfirmDelete" value="Удалить">
				<input class="standartButton" type="submit" name="cancelDelete" value="Отменить">
			</form>
			<br /> 
		<?}?>
		
		<a href="addSemester.php">Добавить новый семестр</a> <br />
		
		<h3>Список всех семестров</h3>
		
		<form name="" action="" method="post">
			<input class="standartButton pr" type="submit" name="editMarkedItem" value="Правки">
			<input class="standartButton de" type="submit" name="deleteMarkedItems" value="Удалить">
			<span style="color: red";><?=$error_delete ?></span> <br /> <br />
			
			<table class="" border='1'>
				<tr>
					<th></th>
					<th>Начало</th>
					<th>Окончание</th>
					<th>Является ли действующим</th>
				</tr>
				<? while (($row = $result_set->fetch_assoc()) != false) { ?>					
					<tr>
						<td><input type="checkbox" name="idItem[]" value="<?=$row[id] ?>"></td>
						<td><?=date('d.m.Y', $row["startDate"]) ?></td>
						<td><?=date('d.m.Y', $row["endDate"]) ?></td>
						<td>
							<? if($row[isActive] == 1) {$isActive = "+"; echo $isActive;}?>
						</td>
					</tr>
					<? $isActive = 0; ?>
				<?}?>
			</table>
		</form> <br />
	</main>
</body>
</html>
<?	$_SESSION["success"] = 0; ?>