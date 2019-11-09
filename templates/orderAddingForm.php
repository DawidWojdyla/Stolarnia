<h3 class="text-center">Dodawanie nowego zamówienia</h3>
<form action="index.php?action=addNewOrder" name="orderAddingForm" id="orderAddingForm" method="post">
	<table class="table table-condensed">
		<?PHP if (isset($_SESSION ['documentNumberError'])):?>
		<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['documentNumberError']?></td></tr>
		<?PHP unset($_SESSION ['documentNumberError']); endif; ?>
		<tr>
			<td>Numer dokumentu:</td>
			<td>
			<div class="fullWidth">
				<select class="inputHeight" name="documentType">
					<option value="PA"  
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'PA'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>PA</option>
					<option value="FV"
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'FV'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>FV</option>
					<option value="RV"
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'RV'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>RV</option>
					<option value="4"
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == '4'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>--</option>
				</select>
				<input class="documentNumber inputHeight" type="text" pattern="\d*" maxlength="6" name="documentNumber" value="<?PHP if (isset($_SESSION['documentNumber'])): ?><?=$_SESSION['documentNumber']?><?PHP unset ($_SESSION['documentNumber']); endif; ?>"/>
				<select class="inputHeight" name="documentBranch">
					<option value="RZ">RZ</option>
					<option value="RA">RA</option>
					<option value="KR">KR</option>
					<option value="NS">NS</option>
					<option value="5">	</option>
				</select>
				</div>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['customerIdError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><div><?=$_SESSION['customerIdError']?></div></div></td></tr>
		<?PHP unset($_SESSION['customerIdError']); endif; ?>
		<tr>
			<td>Wybierz klienta:</td>
			<td>
				<select class="inputHeight" name="customerId">
					<?PHP foreach ($customers as $customer):?>
					<option  value="<?=$customer->id?>" 
						<?PHP if (isset($_SESSION['customerId']) && $_SESSION['customerId'] == $customer->id):?>
						selected
						<?PHP unset ($_SESSION['customerId']); endif;?>
						><?=$customer->surname?> <?=$customer->name?> (<?=$customer->phone?>)</option>
					<?PHP endforeach; ?>
				</select>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['sawNumberError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['sawNumberError']?></div></td></tr>
		<?PHP unset($_SESSION['sawNumberError']); endif; ?>
		<tr>
			<td>Numer piły:</td>
			<td>
				<select class="inputHeight" name="sawNumber" >
					<option value="1"
						<?PHP if (isset($_SESSION['sawNumber']) && $_SESSION['sawNumber'] == "1"):?>
						selected
						<?PHP unset ($_SESSION['sawNumber']); endif;?>>1
					</option>
					<option value="2"
						<?PHP if (isset($_SESSION['sawNumber']) && $_SESSION['sawNumber'] == "2"):?>
						selected
						<?PHP unset ($_SESSION['sawNumber']); endif;?>>2
					</option>
				</select>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['admissionDateError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['admissionDateError']?></div></td></tr>
		<?PHP unset($_SESSION['admissionDateError']); endif; ?>
		<tr>
			<td>
				Data złożenia zamówienia:
			</td>
			<td>
				<input class="inputHeight" type="date" name="admissionDate" value="<?PHP if (isset($_SESSION['admissionDate'])): ?><?=$_SESSION['admissionDate']?><?PHP unset ($_SESSION['admissionDate']);?><?PHP else:?><?=date('Y-m-d')?><?PHP endif; ?>">
			</td>
		</tr>
		<?PHP if(isset($_SESSION['orderCompletionDateError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['orderCompletionDateError']?></div></td></tr>
		<?PHP unset($_SESSION['orderCompletionDateError']); endif; ?>
		<tr>
			<td>
				Data realizacji zamówienia:
			</td>
			<td>
				<input class="inputHeight" type="date" name="orderCompletionDate" value="<?PHP if (isset($_SESSION['orderCompletionDate'])): ?><?=$_SESSION['orderCompletionDate']?><?PHP unset ($_SESSION['orderCompletionDate']);?><?PHP else:?><?=date('Y-m-d')?><?PHP endif; ?>">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="boards" onclick="addNewBoard()";>Dodaj płytę<div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button class="btn btn-default btn-block" type="submit">Dodaj zamówienie</buton>
			</td>
		</tr>
	</table>
</form>