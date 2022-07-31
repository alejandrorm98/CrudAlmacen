<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM
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
            <a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
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
        require_once "./controladores/itemControlador.php";
        $lis = new itemControlador();
        echo $lis->paginar_item_controlador($pagina[1],15,$pagina[0],$_SESSION['busqueda'],$_SESSION['producto'],$_SESSION['proveedor']);
        

        // Usaremos el ciclo para mostrar resultados
        
     ?>
</div>
<?php }  ?>