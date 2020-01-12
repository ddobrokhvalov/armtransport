<?$traffic_types = array('direct'=>'Прямое', 'opposite'=>'Обратное')?>
<h1>Рейсы</h1>
<p>
<?if(count($data['traffics'])):?>
	<table class="sortable">
		<thead>
			<tr>
				<td>Автомобиль</td>
				<td>Маршрут</td>
				<td>Начало рейса</td>
				<td>Окончание рейса</td>
				<td>Груз (кг)</td>
				<td>Направление</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<?foreach($data["traffics"] as $row):?>
				<?$car = Model_Cars::get_by_id($row['car_id']);?>
				<?$way = Model_Ways::get_by_id($row['way_id']);?>
				<tr>
					<td><?=isset($car[0]['name'])?($car[0]['name'].' ( '.$car[0]['reg_mark'].' )'):$row['car_id']?></td>
					<td><?=isset($way[0]['name'])?($way[0]['name'].' ( '.$way[0]['mileage'].' км )'):$row['way_id']?></td>
					<td><?=$row['time_start']?></td>
					<td><?=$row['time_end']?></td>
					<td><?=$row['weight']?></td>
					<td><?=$traffic_types[$row['traffic_type']]?></td>
					<td><a href='/dispatcher/traffic.php?action=control&id=<?=$row["id"]?>'>Управление</a></td>
				</tr>
			<?endforeach;?>
		</tbody>
	</table>
<?else:?>
	Все машины свободны
<?endif;?>
</p>
<a href="/dispatcher/traffic.php?action=new">Начать рейс</a>
