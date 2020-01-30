<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<?$traffic_types = array('direct'=>'Прямое', 'opposite'=>'Обратное')?>
<?$point_types = array('start'=>'Начало рейса', 'pause'=>'Остановка', 'continue'=>'Продолжение пути', 'end'=>'Окончание рейса')?>
<h3>Отчет</h3>
<p>
<a href="/dispatcher/report.php?period=day">За день</a> <a href="/dispatcher/report.php?period=week">За неделю</a> <a href="/dispatcher/report.php?period=month">За месяц</a>
</p>
<p>
	<?if(is_array($data['report']) && count($data['report'])):?>
		<table>
			<tr>
				<th>Маршрут</th>
				<th>Километраж</th>
				<th>Норма перевозки</th>
				<th>Норма расхода топлива</th>
				<th>Фактически перевезено</th>
				<th>Фактический расход топлива</th>
			</tr>
			<?foreach($data['report'] as $way):?>
				<tr>
					<td><?=$way['name']?></td>
					<td><?=$way['mileage']?></td>
					<td><?=$way['norm_cargo_'.$data['period']]?></td>
					<td><?=$way['norm_fuel_'.$data['period']]?></td>
					<td><?=$way['fact_cargo']?></td>
					<td><?=$way['fact_fuel']?></td>
				</tr>
				<tr>
					<td colspan="6">
						<a href="#" id='traffics_<?=$way['id']?>' class='link_traffics'>Рейсы</a>:
						<table width="100%" id='table_traffics_<?=$way['id']?>' class='table_traffics'>
							<tr>
								<th>Автомобиль</th>
								<th>Километраж</th>
								<th>Груз</th>
								<th>Направление</th>
								<th>Начало</th>
								<th>Окончание</th>
							</tr>
							<?foreach($way['traffics'] as $traffic):?>
								<tr>
									<td><?=$traffic['car_name']?> <?=$traffic['reg_mark']?></td>
									<td><?=$traffic['sum_mil']?></td>
									<td><?=$traffic['weight']?></td>
									<td><?=$traffic_types[$traffic['traffic_type']]?></td>
									<td><?=$traffic['time_start']?></td>
									<td><?=$traffic['time_end']?></td>
								</tr>
								<tr>
									<td colspan="6">
										<a href="#" id='traffic_points_<?=$way['id']?>_<?=$traffic['id']?>' class='link_traffic_points'>Пункты:
										<table width="100%" id='table_traffic_points_<?=$way['id']?>_<?=$traffic['id']?>' class='table_traffic_points'>
											<tr>
												<th>Наименование</th>
												<th>Дата/время</th>
												<th>Пробег (от предыдущего пункта)</th>
												<th>Тип пункта</th>
											</tr>
											<?foreach($way['traffic_points'][$traffic['id']] as $traffic_point):?>
												<tr>
													<td><?=$traffic_point['point_name']?></td>
													<td><?=$traffic_point['point_time']?></td>
													<td><?=$traffic_point['mileage']?></td>
													<td><?=$point_types[$traffic_point['point_type']]?></td>
												</tr>
											<?endforeach;?>
										</table>
									</td>
								</tr>
							<?endforeach;?>
						</table>
					</td>
				</tr>
			<?endforeach;?>
		</table>
	<?endif;?>
</p>
