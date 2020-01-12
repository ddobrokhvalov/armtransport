<h1>Водители</h1>
<p>
<?if(count($data['drivers'])):?>
	<table class="sortable">
	<thead>
	<tr>
		<td>Имя</td>
		<td>Фамилия</td>
		<td>Автомобиль</td>
		<td>Логин</td>
	</tr>
	</thead>
	<tbody>
	<?foreach($data["drivers"] as $row):?>
		<?$car = Model_Cars::get_by_id($row['car_id']);?>
		<tr>
			<td><a href='/dispatcher/drivers.php?action=edit&id=<?=$row["id"]?>'><?=$row['firstname']?></a></td>
			<td><a href='/dispatcher/drivers.php?action=edit&id=<?=$row["id"]?>'><?=$row['lastname']?></a></td>
			<td><?=isset($car[0]['name'])?($car[0]['name'].' ( '.$car[0]['reg_mark'].' )'):$row['car_id']?></td>
			<td><?=$row['login']?></td>
		</tr>
	<?endforeach;?>
	</tbody>
	</table>
<?else:?>
	Водители не найдены.
<?endif;?>
</p>
<a href="/dispatcher/drivers.php?action=new">Добавить водителя</a>
