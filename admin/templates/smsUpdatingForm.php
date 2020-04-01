<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Lista SMS</div>
	<table class='table orderListTable'>
		<tr>
			<th>treść szablonu</th><th>aktywny</th>
		</tr>
	<?PHP foreach($smsList as $sms):?>
		<tr class="pointer" id="<?=$sms -> id?>" onclick="showSMSOptions('<?=$sms -> id?>');">
			<td>
				<span id='smsContent<?=$sms -> id?>'><?=$sms -> content?></span>
			</td>
			<td id='activeMark<?=$sms -> id?>'>
				<?PHP if($activeSMSId == $sms -> id): ?><span class="glyphicon glyphicon-ok"></span><script>activeSMSId = <?=$sms -> id?>;</script><?PHP endif; ?>
			</td>
		</tr>
	<?PHP endforeach; ?>
		<tr id="lastRow"></tr>
	</table>
	<div class='btn btn-default btn-block' onclick="addNewSMS();"><span class="glyphicon glyphicon-plus"></span> Nowy SMS</div>
</div>

<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
		<div class="modal-content">
				<div class="modal-body" id='modalBody'>
				</div>
			</div>
		</div>
	</div>
</div>