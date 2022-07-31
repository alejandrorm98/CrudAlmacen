<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE
	</h3>
	<p class="text-justify">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
		</li>
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
		</li>
	</ul>	
</div>
<?php if(!isset($_SESSION['busqueda'])){ ?>
<div class="container-fluid">
    <form class="form-neon" action="<?php echo SERVERURL; ?>ajax/buscaAjax.php" autocomplete="on" method="POST" id='itemS' data-form="save">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">¿Qué item estas buscando?</label>
                        <input type="text" class="form-control" name="busqueda" id="inputSearch" maxlength="30">
                        <input type="hidden" class="form-control" value="<?php echo $pagina[1]; ?>" name="pag" id="inputSearch" maxlength="30">
                        <input type="hidden" class="form-control" value="<?php echo $pagina[0]; ?>/" name="url" id="inputSearch" maxlength="30">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
<?php }else{ ?>

<div class="container-fluid">
    <form class="form-neon" action="<?php echo SERVERURL; ?>ajax/buscaAjax.php" autocomplete="on" method="POST" id='itemS' data-form="save">
        <input type="hidden" name="eliminar-busqueda" value="eliminar">
		<input type='hidden'  value='<?php echo $pagina[0]; ?>/' name='url' >
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Resultados de la busqueda <strong>“<?php echo $_SESSION['busqueda'];?>”</strong>
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="container-fluid">
    <?php 
        require_once "./controladores/movimientoControlador.php";
        $lis = new movimientoControlador();
        echo $lis->paginar_movimiento_controlador($pagina[1],15,$pagina[0],$_SESSION['busqueda'],$_SESSION['producto'],$_SESSION['proveedor']);
        

        // Usaremos el ciclo para mostrar resultados
        
     ?>
</div>
<?php }  ?>