<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<table class='table orderListTable'>
		<tr>
			<th>nazwa</th><th>nr telefonu</th><th>adres</th>
		</tr>
	<?PHP foreach($customers as $customer):?>
		<tr class="pointer"  onclick="showCustomerOptions('<?=$customer -> id?>');">
			<td>
				<span id='name<?=$customer -> id?>'><?=$customer -> name?></span> <span id='surname<?=$customer -> id?>'><?=$customer -> surname?></span>
			</td>
			<td>
				<span id='phone<?=$customer -> id?>'><?=$customer -> phone?></span>
			</td>
			<td>
				<span id='address<?=$customer -> id?>'><?=$customer -> address?></span>
			</td>
		</tr>
	<?PHP endforeach; ?>	
	</table>
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