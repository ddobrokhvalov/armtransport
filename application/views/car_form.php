<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<h1>Создание/редактирование автомобиля</h1>
<p>
	<form method="POST" enctype="multipart/form-data" id="form">
		<table>
			<tr>
				<td>Марка, модель</td>
				<td>
					<input type="text" id="name" name="name" size="100" class="required" value="<?=isset($data['car']['name'])?$data['car']['name']:''?>">
				</td>
			</tr>
			<tr>
				<td>Госномер</td>
				<td>
					<input type="text" id="reg_mark" name="reg_mark" size="100" class="required" value="<?=isset($data['car']['reg_mark'])?$data['car']['reg_mark']:''?>">
				</td>
			</tr>
			<tr>
				<td>Расход топлива (л / 100 км)</td>
				<td>
					<input type="text" id="fuel_cons" name="fuel_cons" size="100" class="required float" value="<?=isset($data['car']['fuel_cons'])?$data['car']['fuel_cons']:''?>">
				</td>
			</tr>
			<tr>
				<td>Маршрут</td>
				<td>
					<select id="way_id" name="way_id" class="required numeric">
						<option value="">Выберите маршрут</option>
						<?foreach($data['ways'] as $way):?>
							<option value='<?=$way["id"]?>' <?if($data['car']['way_id'] == $way['id']):?>selected="selected"<?endif;?>><?=$way["name"]?></option>
						<?endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" class="submit" value="Сохранить"></td>
			</tr>
		</table>
	</form>
</p>
<?if(Controller_user::authorized()):?>
	<a href="/dispatcher/cars.php">Вернуться</a>
<?endif;?>
