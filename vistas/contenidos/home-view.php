
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
	</h3>
	<p class="text-justify">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
	</p>
</div>

<!-- Content -->
<div class="full-box tile-container">
	<!-- Content 
	<a href="client-list/" class="tile">
		<div class="tile-tittle">Clientes</div>
		<div class="tile-icon">
			<i class="fas fa-users fa-fw"></i>
			<p></p>
		</div>
	</a>-->
	
	<a href="<?php echo SERVERURL; ?>client-new/" class="tile">
		<div class="tile-tittle">Movimientos</div>
		<div class="tile-icon">
			<i class="fas fa-pallet fa-fw"></i>
			<p>Agregar Movimiento</p>
		</div>
	</a>
	
	<a href="<?php echo SERVERURL; ?>item-new/" class="tile">
		<div class="tile-tittle">Items</div>
		<div class="tile-icon">
			<i class="far fa-calendar-alt fa-fw"></i>
			<p>Agregar Items</p>
		</div>
	</a>

	<a href="<?php echo SERVERURL; ?>client-list/" class="tile">
		<div class="tile-tittle">Movimientos</div>
		<div class="tile-icon">
			<i class="fas fa-clipboard-list fa-fw"></i>
			<p>Listado de Movimientos</p>
		</div>
	</a>


	<a href="<?php echo SERVERURL; ?>item-list/" class="tile">
		<div class="tile-tittle">Inventario</div>
		<div class="tile-icon">
			<i class="fas fa-store-alt fa-fw"></i>
			<p>Listado de Items</p>
		</div>
	</a>

</div>