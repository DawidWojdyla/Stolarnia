<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Stanowiska</div>
	<table class='table orderListTable'>
		<tr>
			<th>id</th><th>stanowisko</th><th>liczba pracowników</th>
		</tr>
	<?PHP foreach($stands as $stand):?>
		<tr class="pointer" onclick="showStandOptions('<?=$stand -> id?>');">
			<td>
				<span id='stand<?=$stand -> id?>'><?=$stand -> id?></span>
			</td>
			<td>
				<span class="text-capitalize" id='name<?=$stand -> id?>'><?=$stand -> name?></span>
			</td>
			<td>
				<span><?=$stand -> workersAmount?></span>
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