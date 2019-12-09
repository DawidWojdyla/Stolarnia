<?php if(!isset($joinery)) die();?>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-dashboard"></span> Zlecenia <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showOrderAddingForm"><span class="glyphicon glyphicon-plus"></span> Dodaj</a></li>
		<li><a href="index.php?action=showOrderSearchingForm"><span class="glyphicon glyphicon-search"></span> Szukaj</a></li>
		<li><a href="#"><span class="glyphicon glyphicon-pencil"></span> Edytuj</a></li>
		<li><a href="index.php?action=showOrderList"><span class="glyphicon glyphicon-th-list"></span> Lista</a></li>
	</ul>
</li>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Klienci <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showCustomerAddingForm"><span class="glyphicon glyphicon-plus"></span> Dodaj</a></li>
		<li><a href="#"><span class="glyphicon glyphicon-search"></span> Szukaj</a></li>
		<li><a href="#"><span class="glyphicon glyphicon-pencil"></span> Edytuj</a></li>
		<li><a href="index.php?action=showCustomerList"><span class="glyphicon glyphicon-th-list"></span> Lista</a></li>
	</ul>
</li>