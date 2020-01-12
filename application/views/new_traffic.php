<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<h1>Старт рейса</h1>
<p>
	<form method="POST" enctype="multipart/form-data" id="form">
		<table>
			<tr>
				<td>Автомобиль / Маршрут</td>
				<td>
					<select id="car_id" name="car_id" class="required numeric">
						<option value="">Выберите автомобиль / маршрут</option>
						<?foreach($data['cars_ways'] as $car):?>
							<option value='<?=$car["car_id"]?>'><?=$car["car_name"]?> ( <?=$car["reg_mark"]?> ) / <?=$car["way_name"]?> (<?=$car["mileage"]?> км)</option>
						<?endforeach;?>
					</select>
				</td>
			</tr>
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
				<td colspan="2"><input type="submit" name="submit" class="submit" value="Сохранить"></td>
			</tr>
		</table>
	</form>
</p>
<?if(Controller_user::authorized()):?>
	<a href="/dispatcher/traffic.php">Вернуться</a>
<?endif;?>
