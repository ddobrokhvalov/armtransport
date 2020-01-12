<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<?$traffic_types = array('direct'=>'Прямое', 'opposite'=>'Обратное')?>
<?$point_types = array('start'=>'Начало рейса', 'pause'=>'Остановка', 'continue'=>'Продолжение пути', 'end'=>'Окончание рейса')?>
<?if($data['traffic']):?>
<h1>Управление рейсом</h1>
<p>
	<table>
		<tr>
			<td>Начало рейса</td>
			<td><?=$data['traffic']['time_start']?></td>
		</tr>
		<tr>
			<td>Автомобиль</td>
			<td><?=$data['car']['name']?> (<?=$data['car']['reg_mark']?>)</td>
		</tr>
		<tr>
			<td>Маршрут</td>
			<td><?=$data['way']['name']?> (<?=$data['way']['mileage']?> км)</td>
		</tr>
		<tr>
			<td>Направление</td>
			<td><?=$traffic_types[$data['traffic']['traffic_type']]?></td>
		</tr>
	</table>
</p>
<p>
	<h2>Пункты рейса</h2>
	<table>
		<tr>
			<td>Наименование</td>
			<td>Дата/время</td>
			<td>Пробег (от предыдущего пункта)</td>
			<td>Тип пункта</td>
		</tr>
		<?$sum_mileage = 0;?>
		<?foreach($data['traffic_points'] as $point):?>
			<tr>
				<td><?=$point['point_name']?></td>
				<td><?=$point['point_time']?></td>
				<td><?=$point['mileage']?> км</td>
				<td><?=$point_types[$point['point_type']]?></td>
			</tr>
			<?$sum_mileage += $point['mileage']?>
		<?endforeach;?>
	</table>
</p>
	<?
	$last_point = end($data['traffic_points']);
	
	?>
	<?if($last_point['point_type'] == 'start' || $last_point['point_type'] == 'continue'):?>
		<p>
		<a href="#" id="link_form_pause">Остановка</a>
		</p>
		<p>
		<form method="POST" enctype="multipart/form-data" id="form_pause">
			<table>
				<tr>
					<td>Наименование</td>
					<td><input type="text" id="point_name" name="point_name" size="100" class="required" value=""></td>
				</tr>
				<tr>
					<td>Пробег (от предыдущего пункта)</td>
					<td><input type="text" id="mileage" name="mileage" size="100" class="required numeric" value=""></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="hidden" id="point_type" name="point_type" value="pause">
						<input type="submit" name="submit" class="submit" value="Сохранить">
					</td>
				</tr>
			</table>
		</form>
		</p>
		
		<p>
		<a href="#" id="link_form_end">Окончание рейса</a>
		</p>
		<p>
		<form method="POST" enctype="multipart/form-data" id="form_end">
			<table>
				<tr>
					<td>Пробег (от предыдущего пункта)</td>
					<td><input type="text" id="mileage2" name="mileage" size="100" class="required numeric" value="<?=($data['way']['mileage'] - $sum_mileage > 0)?($data['way']['mileage'] - $sum_mileage):0?>"></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="hidden" id="point_type2" name="point_type" value="end">
						<input type="submit" name="submit" class="submit" value="Сохранить">
					</td>
				</tr>
			</table>
		</form>
		</p>
	<?endif;?>
	<?if($last_point['point_type'] == 'pause'):?>
		<p>
		<form method="POST" enctype="multipart/form-data" id="form">
			<table>
				<tr>
					<td colspan="2">
						<input type="hidden" id="point_type3" name="point_type" value="continue">
						<input type="submit" name="submit" class="submit" value="Продолжить рейс">
					</td>
				</tr>
			</table>
		</form>
		</p>
	<?endif;?>
<?else:?>
	<h1>Начало рейса</h1>
	<p>
		<table>
			<tr>
				<td>Автомобиль</td>
				<td><?=$data['car']['name']?> (<?=$data['car']['reg_mark']?>)</td>
			</tr>
			<tr>
				<td>Маршрут</td>
				<td><?=$data['way']['name']?> (<?=$data['way']['mileage']?> км)</td>
			</tr>
			
		</table>
	</p>
	<br>
	<p>
		<form method="POST" enctype="multipart/form-data" id="form">
			<table>
				<tr>
					<td>Направление</td>
					<td>
						<select id="traffic_type" name="traffic_type" class="required">
							<option value="">Выберите направление</option>
							<option value="direct">Прямое</option>
							<option value="opposite">Обратное</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Груз (кг)</td>
					<td>
						<input type="text" id="weight" name="weight" size="100" class="required numeric" value="<?=isset($data['driver']['lastname'])?$data['driver']['lastname']:''?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="hidden" id="point_type4" name="point_type" value="start">
						<input type="submit" name="submit" class="submit" value="Начать рейс">
					</td>
				</tr>
			</table>
		</form>
	</p>
<?endif;?>
