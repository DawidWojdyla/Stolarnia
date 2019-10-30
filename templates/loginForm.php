<?php if(!isset($joinery)) die(); ?>
<div id="loginFormWrapper" class="forms">
	<form action = "index.php?action=login" method = "post">
		<table>
			<tr>
				<td>Stanowisko:</td>
				<td> 
					<select name="stand">
						<option value="1">Piła 1</option>
						<option value="2">Piła 2</option>
						<option value="3">Okleiniarka</option>
						<option value="4">Sklep</option>
					 </select>
				</td>
			</tr>
			<tr>
				<td>Hasło:</td>
				<td><input type="password" name="password"></td>
			</tr>
			<tr>
				<td><input type="submit" value="Zaloguj"></td>
			</tr>
		</table>
	</form>
</div>