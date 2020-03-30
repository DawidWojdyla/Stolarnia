<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Realizuj zlecenie: </div>
	<form action="index.php?action=findTheOrder" name="orderSearchingForm" id="orderSearchingForm" method="post" autocomplete="off">
		<div class="tabContainer">
			<div class="container" style=" padding-top : 5px; padding-bottom: 5px; max-width: 600px; margin-bottom: 20px;">
				<div class="row noMargin">
					<div class="col-xs-3 smallerPadding">
						<select id='documentTypeSelect' class="form-control text-center" name="documentType">
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
					</div>
					<div class="col-xs-6 smallerPadding">
						<input id='documentNumberInput' class="form-control text-center" type="text" pattern="\d*" maxlength="6" name="documentNumber" value="<?=$documentNumber?>" required/>
					</div>
					<div class="col-xs-3 smallerPadding">
						<select class="form-control text-center" name="documentBranch">
							<option value="RZ" <?PHP if ($documentBranch == 'RZ'):?>selected<?PHP endif;?>>RZ</option>
							<option value="RA" <?PHP if ($documentBranch == 'RA'):?>selected<?PHP endif;?>>RA</option>
							<option value="KR" <?PHP if ($documentBranch == 'KR'):?>selected<?PHP endif;?>>KR</option>
							<option value="NS" <?PHP if ($documentBranch == 'NS'):?>selected<?PHP endif;?>>NS</option>
							<option value="5" <?PHP if ($documentBranch == '5'):?>selected<?PHP endif;?>>	</option>
						</select>
					</div>
				</div>
				<div>
					<button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-expand"></span> Realizuj</button>
				</div>
			</div>
		</div>
	</form>
</div>