<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS
    </h3>
    <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum delectus eos enim numquam fugit optio accusantium, aperiam eius facere architecto facilis quibusdam asperiores veniam omnis saepe est et, quod obcaecati.
    </p>
</div>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
	
                
                <?php 
                require_once "./controladores/itemControlador.php";
                $lis = new itemControlador();

                if ((isset($_SESSION['producto']) && isset($_SESSION['proveedor'])) ) {
                    echo $lis->paginar_item_controlador($pagina[1],15,$pagina[0],"",$_SESSION['producto'],$_SESSION['proveedor']);
                }else{
                    echo $lis->paginar_item_controlador($pagina[1],15,$pagina[0],"","","");
                }
                // Usaremos el ciclo para mostrar resultados
                
                    ?>

            
    
	
</div>