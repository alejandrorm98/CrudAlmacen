<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM
    </h3>
    <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque laudantium necessitatibus eius iure adipisci modi distinctio. Earum repellat iste et aut, ullam, animi similique sed soluta tempore cum quis corporis!
    </p>
</div>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
	<form action="<?php echo SERVERURL; ?>ajax/itemAjax.php" class="form-neon FormularioAjax" method="POST" 
	data-form="save" autocomplete="off" id='itemN'>
		<fieldset>
			<legend><i class="far fa-plus-square"></i> &nbsp; Información del item</legend>
			<div class="container-fluid">
				<div class="row" >
					<div class="col-12 col-md-5">
						<div class="form-group">
							<label for="item_codigo" class="bmd-label-floating">Producto (Sin espacio)</label>
							<input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9-']{1,140}" class="form-control" name="producto" id="item_nombre" maxlength="30" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required="">
						</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="form-group">
							<label for="item_codigo" class="bmd-label-floating">Proveedor (Sin espacio)</label>
							<input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9-']{1,45}" class="form-control" name="proveedor" id="item_codigo" maxlength="45"
							onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required="">
						</div>
					</div>
					<div class="col-12 col-md-1">
						<div class="form-group">
							<select action="<?php echo SERVERURL; ?>ajax/itemAjax.php/" class="form-control selectAjax" name="cantidad" >
								<option value="0" selected="" name="0">Cantidad</option>
								<option value="1" name="1">1</option>
								<option value="2" name="2">2</option>
								<option value="3" name="3">3</option>
								<option value="4" name="4">4</option>
								<option value="5" name="5">5</option>
								<option value="0" name="0">0</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="form-group">
							<label for="item_nombre" class="bmd-label-floating">Inventario</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-']{1,150}" class="form-control" name="inventario0" id="item_nombre" maxlength="140"  required="" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
						</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="form-group">
							<label for="item_stock" class="bmd-label-floating">Serial</label>
							<input type="num" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9().,#\-']{1,140}" class="form-control" name="serial0" id="item_stock" maxlength="140" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" maxlength="9" required="">
						</div>
					</div>
					
					<div class="col-12 col-md-5 proveerdor">
						
					</div>
					<?php
					/*
					if (isset($_SESSION['div'])) {
						echo $_SESSION['div'];
						unset($_SESSION['div']);
					}*/
					 	 
					?>
					

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