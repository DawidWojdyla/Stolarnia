<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Znajdź zlecenie: </h3>
<form action="index.php?action=findOrderByDocumentNumber" name="orderSearchingForm" id="orderSearchingForm" method="post" autocomplete="off">
	<table class="table table-condensed">
		<tr>
			<td>Nr dokumentu:</td>
			<td>
				<div style="min-width: 160px;" class="fullWidth">
					<select id='documentTypeSelect' class="inputHeight" name="documentType">
						<option value="PA"  
							<?PHP if ($documentType == 'PA'):?>
							selected
							<?PHP endif;?>>PA</option>
						<option value="FV"
							<?PHP if ($documentType == 'FV'):?>
							selected
							<?PHP endif;?>>FV</option>
						<option value="RV"
							<?PHP if ($documentType == 'RV'):?>
							selected
							<?PHP endif;?>>RV</option>
					</select>
					<input id='documentNumberInput' class="documentNumber inputHeight" type="text" pattern="\d*" maxlength="6" name="documentNumber" value="<?=$documentNumber?>" required/>
					<select class="inputHeight" name="documentBranch">
						<option value="RZ" <?PHP if ($documentBranch == 'RZ'):?>selected<?PHP endif;?>>RZ</option>
						<option value="RA" <?PHP if ($documentBranch == 'RA'):?>selected<?PHP endif;?>>RA</option>
						<option value="KR" <?PHP if ($documentBranch == 'KR'):?>selected<?PHP endif;?>>KR</option>
						<option value="NS" <?PHP if ($documentBranch == 'NS'):?>selected<?PHP endif;?>>NS</option>
						<option value="5" <?PHP if ($documentBranch == '5'):?>selected<?PHP endif;?>>	</option>
					</select>
				</div>
			</td>
		</tr>
		<tr><td colspan='2'><button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Znajdź</button></td></tr>
	</table>
</form>
	
