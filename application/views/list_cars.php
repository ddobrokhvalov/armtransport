<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<h3>Автомобили</h3>
<p>
<?if(count($data['cars'])):?>
	<table class="sortable">
	<thead>
	<tr>
		<td>Марка, модель</td>
		<td>Госномер</td>
		<td>Расход топлива (л / 100 км)</td>
		<td>Маршрут</td>
	</tr>
	</thead>
	<tbody>
	<?foreach($data["cars"] as $row):?>
		<?$way = Model_Ways::get_by_id($row['way_id']);?>
		<tr>
			<td><a href='/dispatcher/cars.php?action=edit&id=<?=$row["id"]?>'><?=$row['name']?></a></td>
			<td><?=$row['reg_mark']?></td>
			<td><?=$row['fuel_cons']?></td>
			<td><?=isset($way[0]['name'])?$way[0]['name']:$row['way_id']?></td>
		</tr>
	<?endforeach;?>
	</tbody>
	</table>
<?else:?>
	Автомобили не найдены.
<?endif;?>
</p>
<p>

</p>	
<a href="/dispatcher/cars.php?action=new">Добавить автомобиль</a>

