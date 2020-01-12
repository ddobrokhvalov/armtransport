<?require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header2.php';?>
<h1>Создание/редактирование водителя</h1>
<p>
	<form method="POST" enctype="multipart/form-data" id="form">
		<table>
			<tr>
				<td>Имя</td>
				<td>
					<input type="text" id="firstname" name="firstname" size="100" class="required" value="<?=isset($data['driver']['firstname'])?$data['driver']['firstname']:''?>">
				</td>
			</tr>
			<tr>
				<td>Фамилия</td>
				<td>
					<input type="text" id="lastname" name="lastname" size="100" class="required" value="<?=isset($data['driver']['lastname'])?$data['driver']['lastname']:''?>">
				</td>
			</tr>
			<tr>
				<td>Автомобиль</td>
				<td>
					<select id="car_id" name="car_id" class="required numeric">
						<option value="">Выберите автомобиль</option>
						<?foreach($data['cars'] as $car):?>
							<option value='<?=$car["id"]?>' <?if($data['driver']['car_id'] == $car['id']):?>selected="selected"<?endif;?>><?=$car["name"]?> ( <?=$car["reg_mark"]?> )</option>
						<?endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Логин</td>
				<td>
					<input type="text" id="login" name="login" size="100" class="required" value="<?=isset($data['driver']['login'])?$data['driver']['login']:''?>">
				</td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td>
					<input type="text" id="password" name="password" size="100" class="<?if($_GET['action'] == 'new'):?>required<?endif;?>" value="">
					<?if($_GET['action'] == 'edit'):?><br><small>Оставьте пустым, если не нужно менять пароль</small><?endif;?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" class="submit" value="Сохранить"></td>
			</tr>
		</table>
	</form>
</p>
<?if(Controller_user::authorized()):?>
	<a href="/dispatcher/drivers.php">Вернуться</a>
<?endif;?>
