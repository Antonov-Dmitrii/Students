<?php
	session_start();
	require_once "../../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../../lib/db.php";
	
	$db = DB::getObject();
	
	if(isset($_GET["idGroup"])) {
		$idGroup = $_GET["idGroup"];
		$idSubject = $_GET["idSubject"];
		
		$result_set = $db->getRowWhere("groups", "id", $idGroup);
		$row = $result_set->fetch_assoc();
		$groupName = $row[name];
		
		$result_set = $db->getRowWhere("subjects", "id", $idSubject);
		$row = $result_set->fetch_assoc();
		$subjectName = $row[name];
	}
	
	//определить дату начала и конца текущего семестра
	$result_set = $db->getRowWhere("start_and_end_semesters_dates", "isActive", 1);
	$row = $result_set->fetch_assoc();
	
	$result_set1 = $db->getDatesForJournal($row[startDate], $row[endDate], $idGroup, $idSubject);
	
	$result_set2 = $db->getRowWhereOrderNotDeleted("students", "idGroup", $idGroup, "name");
	
	$numberOneAfterAnother = 1;
	$arrayForDaysInCycle;
	$i = 0; 
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
		<h3>Журнал оценок</h3>
		<p><b>Семестр</b> <?=date('d.m.Y', $row[startDate])?> - <?=date('d.m.Y', $row[endDate])?></p>
		<p><b>Предмет</b> - <?=$subjectName?></p>
		<p><b>Группа</b> - <?=$groupName?></p>
		<a href="addDateMark.php?idGroup=<?=$idGroup?>&idSubject=<?=$idSubject?>">Добавить дату и вид оценки</a> <br />
		<a href="printSchedule.php?idSubject=<?=$idSubject?>&idGroup=<?=$idGroup?>">Распечатать журнал</a> <br /> <br />
		
		<form name="" action="" method="post">	
			<table border='1'>
				<tr>
					<th>№п/п</th>
					<th>ФИО</th>
					<? while (($row1 = $result_set1->fetch_assoc()) != false) { ?>
						<th>
							<?=date('d.m.Y', $row1[date])?>
							<?
							switch($row1[type]) {
								case "Практика": {echo "<br />"."ПЗ"; break;}
								case "Курсовая работа": {echo "<br />"."Курс.раб."; break;}
								case "Зачет": {echo "<br />"."Зачет"; break;}
								case "Экзамен": {echo "<br />"."Экзамен"; break;}
							}?>
						</th>
						<? $arrayForDaysInCycle[] = $row1[date]; ?>
					<?}?>
				</tr>
				<? while (($row2 = $result_set2->fetch_assoc()) != false) { ?>
					<tr>
						<td><?=$numberOneAfterAnother?></td>
						<td><?=$row2[name]?></td>
						<? for($i = 0; $i < count($arrayForDaysInCycle); $i++) {?>
							<? $result_set3 = $db->getMark($row2[id], $idSubject, $arrayForDaysInCycle[$i]); ?>
							<? if($result_set3->num_rows != 0){ ?>
								<? while (($row3 = $result_set3->fetch_assoc()) != false) { ?>
									<? $row3[mark] == 0 ? $mark = "" : $mark = $row3[mark]; ?>
									<td><a class="inCells" href="editMark.php?idMark=<?=$row3[id]?>&idSubject=<?=$idSubject?>&idGroup=<?=$idGroup?>"><?=$mark?></a></td>
								<?}?>
							<?}
							else {?>
								<td><a class="inCells" href="addMark.php?idStudent=<?=$row2[id]?>&idSubject=<?=$idSubject?>&date=<?=$arrayForDaysInCycle[$i]?>&idGroup=<?=$idGroup?>"></a></td>
							<?}?>
						<?}?>
					</tr>
					<? $numberOneAfterAnother++; ?>
				<?}?>
			</table>
		
		</form>
	</main>
</body>
</html>