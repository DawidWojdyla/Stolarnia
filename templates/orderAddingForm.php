<h2>Dodawanie nowego zamówienia</h2>
<form action="index.php?action=addNewOrder" name="orderAddingForm" id="orderAddingForm" method="post">
	<table>
		<?PHP if (isset($_SESSION ['documentNumberError'])):?>
		<tr><td colspan="2"><?=$_SESSION ['documentNumberError']?></td></tr>
		<?PHP unset($_SESSION ['documentNumberError']); endif; ?>
		<tr>
			<td>Numer dokumentu:</td>
			<td>
				<select name="documentType">
					<option value="PA"  
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'PA'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>PA
					</option>
					<option value="FV"
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'FV'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>FV
					</option>
					<option value="RV"
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'RV'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>RV
					</option>
					<option value="4"
						<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == '4'):?>
						selected
						<?PHP unset ($_SESSION['documentType']); endif;?>>Brak
					</option>
				</select>
				<input type="text" pattern="\d*" maxlength="6" name="documentNumber" value="<?PHP if (isset($_SESSION['documentNumber'])): ?><?=$_SESSION['documentNumber']?><?PHP unset ($_SESSION['documentNumber']); endif; ?>"/>
				<select name="documentBranch">
					<option value="RZ">RZ</option>
					<option value="RA">RA</option>
					<option value="KR">KR</option>
					<option value="NS">NS</option>
					<option value="5">	</option>
				</select>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['customerIdError'])):?>
		<tr><td colspan="2"><div class="error"><?=$_SESSION['customerIdError']?></div></td></tr>
		<?PHP unset($_SESSION['customerIdError']); endif; ?>
		<tr>
			<td>Wybierz klienta:</td>
			<td>
				<select name="customers">
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
		<tr><td colspan="2"><div class="error"><?=$_SESSION['sawNumberError']?></div></td></tr>
		<?PHP unset($_SESSION['sawNumberError']); endif; ?>
		<tr>
			<td>Numer piły:</td>
			<td>
				<select name="sawNumber" >
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
		<tr><td colspan="2"><div class="error"><?=$_SESSION['admissionDateError']?></div></td></tr>
		<?PHP unset($_SESSION['admissionDateError']); endif; ?>
		<tr>
			<td>
				Data złożenia zamówienia:
			</td>
			<td>
				<input type="date" name="admissionDate" value="<?PHP if (isset($_SESSION['admissionDate'])): ?><?=$_SESSION['admissionDate']?><?PHP unset ($_SESSION['admissionDate']);?><?PHP else:?><?=date('Y-m-d')?><?PHP endif; ?>">
			</td>
		</tr>
		<?PHP if(isset($_SESSION['admissionDateError'])):?>
		<tr><td colspan="2"><div class="error"><?=$_SESSION['admissionDateError']?></div></td></tr>
		<?PHP unset($_SESSION['admissionDateError']); endif; ?>
		<tr>
			<td>
				Data realizacji zamówienia:
			</td>
			<td>
				<input type="date" name="admissionDate" value="<?PHP if (isset($_SESSION['orderCompletionDate'])): ?>
					<?=$_SESSION['orderCompletionDate']?>
					<?PHP unset ($_SESSION['orderCompletionDate']);?>
					<?PHP else:?><?=date('Y-m-d')?><?PHP endif; ?>">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button type="submit">Dodaj</buton><button type="button" onclick="location.href='index.php?action=showMenu';">Anuluj</button>
			</td>
		</tr>
	</table>
</form>