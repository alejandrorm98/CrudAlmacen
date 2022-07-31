<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES
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
			<a class="active" href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE </a>
		</li>
	</ul>	
</div>

<div class="container-fluid">
	
                
                <?php 
                require_once "./controladores/movimientoControlador.php";
                $lis = new movimientoControlador();

                if ((isset($_SESSION['producto']) && isset($_SESSION['proveedor'])) ) {
                    echo $lis->paginar_movimiento_controlador($pagina[1],15,$pagina[0],"",$_SESSION['producto'],$_SESSION['proveedor']);
                }else{
                    echo $lis->paginar_movimiento_controlador($pagina[1],15,$pagina[0],"","","");
                }
                // Usaremos el ciclo para mostrar resultados
                
                    ?>

            
    
	
</div>