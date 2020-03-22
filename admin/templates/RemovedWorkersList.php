<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
<?PHP if($removedWorkers): ?>
	<div class="text-center header">Lista usuniętych pracowników</div>
	<table class='table orderListTable'>
		<tr>
			<th>imię</th><th>nazwisko</th><th>stanowiska</th>
		</tr>
	<?PHP foreach($removedWorkers as $worker):?>
		<tr id='<?=$worker -> workerId?>' class="pointer" onclick="showWorkerOptions('<?=$worker -> workerId?>');">
			<td>
				<span id='name<?=$worker -> workerId?>'><?=$worker -> name?></span>
			</td>
			<td>
				<span id='surname<?=$worker -> workerId?>'><?=$worker -> surname?></span>
			</td>
			<td>
				<span id='stands<?=$worker -> workerId?>'><?=$worker -> standsNames?></span>
				<span style="display: none;" id='standsIds<?=$worker -> workerId?>'><?=$worker -> standsIds?></span>
			</td>
		</tr>
	<?PHP endforeach;?>	
	</table>
	<?PHP else: ?>
	<div class="text-center header">Lista usuniętych pracowników jest pusta</div>
	<?PHP endif; ?>
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