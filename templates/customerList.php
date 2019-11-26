<h3 class="text-center">Klienci</h3>
<table class='table'>
	<tr>
		<th>Nazwa</th><th>Nr telefonu</th><th>Adres</th>
	</tr>
<?PHP foreach($customerList as $customer):?>
	<tr>
		<td>
			<?=$customer->surname." ".$customer->name?>
		</td>
		<td>
			<?=$customer->phone?>
		</td>
		<td>
			<?=$customer->address?>
		</td>
	</tr>
<?PHP endforeach; ?>	
</table>