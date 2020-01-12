<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<h1>Маршруты</h1>
<p>
<?if(count($data['ways'])):?>
	<table class="sortable">
	<thead>
	<tr>
		<td>Наименование</td>
		<td>Километраж (км)</td>
		<td>Норма перевозки в день / нед / мес (кг)</td>
		<td>Норма затрат топлива в день / нед / мес (л)</td>
	</tr>
	</thead>
	<tbody>
	<?foreach($data["ways"] as $row):?>
		<tr>
			<td><a href='/dispatcher/ways.php?action=edit&id=<?=$row["id"]?>'><?=$row['name']?></a></td>
			<td><?=$row['mileage']?></td>
			<td><?=$row['norm_cargo_day']?> / <?=$row['norm_cargo_week']?> / <?=$row['norm_cargo_month']?></td>
			<td><?=$row['norm_fuel_day']?> / <?=$row['norm_fuel_week']?> / <?=$row['norm_fuel_month']?></td>
		</tr>
	<?endforeach;?>
	</tbody>
	</table>
<?else:?>
	Маршруты не найдены.
<?endif;?>
</p>
<p>

</p>	
<a href="/dispatcher/ways.php?action=new">Создать маршрут</a>
