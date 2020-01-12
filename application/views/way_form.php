<h1>Создание/редактирование маршрута</h1>
<p>
	<form method="POST" enctype="multipart/form-data" id="form">
		<table>
			<tr>
				<td>Наименование</td>
				<td>
					<input type="text" id="name" name="name" size="100" class="required" value="<?=isset($data['way']['name'])?$data['way']['name']:''?>">
				</td>
			</tr>
			<tr>
				<td>Километраж (км)</td>
				<td>
					<input type="text" id="mileage" name="mileage" size="100" class="required numeric" value="<?=isset($data['way']['mileage'])?$data['way']['mileage']:''?>">
				</td>
			</tr>
			<tr>
				<td>Норма перевозки в день (кг)</td>
				<td>
					<input type="text" id="norm_cargo_day" name="norm_cargo_day" size="100" class="required numeric" value="<?=isset($data['way']['norm_cargo_day'])?$data['way']['norm_cargo_day']:''?>">
				</td>
			</tr>
			<tr>
				<td>Норма перевозки в неделю (кг)</td>
				<td>
					<input type="text" id="norm_cargo_week" name="norm_cargo_week" size="100" class="required numeric" value="<?=isset($data['way']['norm_cargo_week'])?$data['way']['norm_cargo_week']:''?>">
				</td>
			</tr>
			<tr>
				<td>Норма перевозки в месяц (кг)</td>
				<td>
					<input type="text" id="norm_cargo_month" name="norm_cargo_month" size="100" class="required numeric" value="<?=isset($data['way']['norm_cargo_month'])?$data['way']['norm_cargo_month']:''?>">
				</td>
			</tr>
			<tr>
				<td>Норма затрат топлива в день (л)</td>
				<td>
					<input type="text" id="norm_fuel_day" name="norm_fuel_day" size="100" class="required numeric" value="<?=isset($data['way']['norm_fuel_day'])?$data['way']['norm_fuel_day']:''?>">
				</td>
			</tr>
			<tr>
				<td>Норма затрат топлива в неделю (л)</td>
				<td>
					<input type="text" id="norm_fuel_week" name="norm_fuel_week" size="100" class="required numeric" value="<?=isset($data['way']['norm_fuel_week'])?$data['way']['norm_fuel_week']:''?>">
				</td>
			</tr>
			<tr>
				<td>Норма затрат топлива в месяц (л)</td>
				<td>
					<input type="text" id="norm_fuel_month" name="norm_fuel_month" size="100" class="required numeric" value="<?=isset($data['way']['norm_fuel_month'])?$data['way']['norm_fuel_month']:''?>">
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" class="submit" value="Сохранить"></td>
			</tr>
		</table>
	</form>
</p>
<?if(Controller_user::authorized()):?>
	<a href="/dispatcher/ways.php">Вернуться</a>
<?endif;?>
