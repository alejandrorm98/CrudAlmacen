<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE
	</h3>
	<p class="text-justify">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem odit amet asperiores quis minus, dolorem repellendus optio doloremque error a omnis soluta quae magnam dignissimos, ipsam, temporibus sequi, commodi accusantium!
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
		</li>
	</ul>	
</div>

<div class="container-fluid">
	<form action="<?php echo SERVERURL; ?>ajax/movAjax.php/" class="form-neon FormularioAjax" method="POST" 
	data-form="loans" autocomplete="off" id='itemN'>
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-12">
						<div class="form-group">
							<select action="<?php echo SERVERURL; ?>ajax/movAjax.php/" class="form-control selectAjax" name="producto" required="">
								<option value="" selected="" disabled="" name="producto">Seleccione un Producto</option>
								<?php 
								require_once "./controladores/movimientoControlador.php";
								$lis = new movimientoControlador();

								
								echo $lis->select_movimiento_controlador('');
								// Usaremos el ciclo para mostrar resultados
							
								?>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-12">
						<div class="form-group proveerdor">
							
						</div>
					</div>
					<div class="col-12 col-md-2">
						<div class="form-group">
							<label for="cliente_apellido" class="bmd-label-floating">Cantidad</label>
							<input type="number" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="cantidad" id="cliente_apellido" maxlength="40" required="">
						</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="form-group">
							<label for="cliente_telefono" class="bmd-label-floating">Destino</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="destino" id="cliente_telefono" maxlength="30">
						</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="form-group">
							<label for="cliente_direccion" class="bmd-label-floating">Recibe</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="recibe" id="cliente_direccion" maxlength="30"->
						</div>
					</div>
					<div class="col-12 col-md-12">
						<div class="form-group">
							<label for="cliente_direccion" class="bmd-label-floating">Observacion</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="observacion" id="cliente_direccion" maxlength="100">
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<br><br><br>
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
			&nbsp; &nbsp;
			<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
		</p>
	</form>
</div>