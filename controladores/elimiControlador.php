<?php

	if($peticionAjax){
		require_once "../modelos/elimiModelo.php";
	}else{
		require_once "./modelos/elimiModelo.php";
	}
      /*
    require_once "../modelos/elimiModelo.php";*/
    
    
	class elimiControlador extends elimiModelo{

        /*--------- Controlador agregar usuario ---------*/
		public function paginar_elimiI_controlador($pag,$registros,$url,$busqueda,$prod,$prove){
            $pag=mainModel::limpiar_cadena($pag);
            $registros=mainModel::limpiar_cadena($registros);

            $url=mainModel::limpiar_cadena($url);
            $ur=$url;
            $url=SERVERURL.$url."/";

            $busqueda=mainModel::limpiar_cadena($busqueda);
            $tabla="";
            
            $pag=(isset($pag) && $pag>0) ? (int) $pag : 1 ;
            $inicio=($pag>0) ? (($pag*$registros)-$registros) : 0 ;

            if (($prod!="" && $prove!="") && (isset($busqueda) && $busqueda!="")){
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM eliminado where ((producto='$prod' and proveedor='$prove' and movimiento in (0,3)) and (id_producto LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%'))  ORDER BY fecha_elimina desc LIMIT $inicio, $registros";
            }elseif ($prod!="" && $prove!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM eliminado where producto='$prod' and proveedor='$prove' and movimiento in (0,3)  ORDER BY fecha_ingreso asc LIMIT $inicio, $registros";
            }elseif (isset($busqueda) && $busqueda!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) as cantidad FROM eliminado WHERE ((movimiento in (0,3)) AND (id_producto LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%')) GROUP BY producto, proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
            }else {
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) AS cantidad FROM eliminado WHERE movimiento in (0,3) GROUP BY producto,proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
            }
            
            $conexion=mainModel::conectar();
            /*
            $datos=mainModel::ejecutar_consulta_simple($consulta);

            $total=count($datos);*/
            $datos= $conexion->query($consulta);
            $datos=$datos->fetchAll();

            $total=$conexion-> query("SELECT FOUND_ROWS()");
            $total=(int) $total->fetchColumn();

            $Npaginas=ceil($total/$registros);

            if ($prod!="" && $prove!="") {
                $tabla.='<div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr class="text-center roboto-medium">
                                    <th>Id</th>
                                    <th>PRODUCTO</th>
                                    <th>PROVEEDOR</th>
                                    <th>SERIAL</th>
                                    <th>INVENTARIO</th>
                                    <th>FECHA INGRESO</th>
                                    <th>FECHA ELIMINA</th>
                                    <th>USR ELIMINA</th>
                                    <th>USR DIGITA</th>
                                    <th>MOVIMIENTO</th>
                                    <th>OBSERVACION</th>
                                </tr>
                            </thead>
                            <tbody>';
            } else {
                $tabla.='<div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr class="text-center roboto-medium">
                                    <th>#</th>
                                    <th>PRODUCTO</th>
                                    <th>PROVEEDOR</th>
                                    <th>CANTIDAD</th>
                                    <th>DETALLE</th>
                                </tr>
                            </thead>
                            <tbody>';
            }
            
            if ($total>=1 && $pag<=$Npaginas) {
                $contador=$inicio+1;
                $reg_i=$inicio+1;
                foreach($datos as $result) {
                    if ($prod!="" && $prove!="") {
                        $tabla.="<tr class='text-center' >
                                <td>".$result['id_producto']."</td>
                                <td>".$result['producto']."</td>
                                <td>".$result['proveedor']."</td>
                                <td>".$result['serial']."</td>
                                <td>".$result['inventario']."</td>
                                <td>".$result['fecha_elimina']."</td>
                                <td>".$result['fecha_ingreso']."</td>
                                <td>".$result['usr_elimina']."</td>
                                <td>".$result['usr_digita']."</td>
                                <td>".$result['movimiento']."</td>
                                <td>
                                    <button type='submit' class='btn btn-info' data-toggle='popover' data-trigger='hover' title='Observacion' data-content='".$result['observacion']."'>
                                    <i class='fas fa-info-circle'></i>
                                    </button>
                                </td>
                                </tr>";
                    $contador++;
                    } else {
                        $tabla.="<tr class='text-center' >
                                <td>".$contador."</td>
                                <td>".$result['producto']."</td>
                                <td>".$result['proveedor']."</td>
                                <td>".$result['cantidad']."</td>
                                <td>
                                    <form action='".SERVERURL."ajax/buscaAjax.php' method='POST' >
                                        <input type='hidden' class='form-control' value='".$result['producto']."' name='producto' id='inputSearch' maxlength='30'>
                                        <input type='hidden' class='form-control' value='".$result['proveedor']."' name='proveedor' id='inputSearch' maxlength='30'>
                                        <input type='hidden'  value='".$ur."/' name='url' >
                                        <button type='submit' class='btn btn-info' data-toggle='popover' data-trigger='hover' title='Seriales e Inventarios' data-content='Fecha ingreso'>
                                            <i class='fas fa-info-circle'></i>
                                        </button>
                                    </form>    
                                </td>
                                </tr>";
                    $contador++;
                    }
                                   
                }
                $reg_f= $contador-1;
            } else {
                if ($total>=1) {
                    $tabla.='<tr class="text-center"><td colspan="7"><a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click para recargar el listado</a></td> </tr>';
                } else {
                    $tabla.='<tr class="text-center"><td colspan="11">No hay registros en el sistema</td> </tr>';
                }
            }
            


            $tabla.='</tbody></table></div>';
            
            if (($prod!="" && $prove!="") &&  $total>=1) {
                $tabla.='<div class="container-fluid">
                            <ul class="list-unstyled page-nav-tabs">
                                
                                <li>
                                    <form  action="'.SERVERURL.'ajax/buscaAjax.php" autocomplete="on" method="POST" data-form="save">
                                        <input type="hidden" name="back" value="back">
                                        <input type="hidden"  value="'.$ur.'/" name="url" >
                                        <p class="text-left"">
                                        <button class="btn btn-danger" type="submit">ATRÁS</button>
                                        </p>        
                                    </form>
                                </li>
                                <li>
                                    <p class="text-right">Mostrando '.$reg_i.' al '.$reg_f.' de un total de '.$total.'</p>
                                </li>
                            </ul>
                        </div>';
            }elseif($total>=1){
                $tabla.='<p class="text-right">Mostrando '.$reg_i.' al '.$reg_f.' de un total de '.$total.'</p>';
            }else{
                $tabla.='<div class="container-fluid">
                            <ul class="list-unstyled page-nav-tabs">
                                
                                <li>
                                    <form  action="'.SERVERURL.'ajax/buscaAjax.php" autocomplete="on" method="POST" data-form="save">
                                        <input type="hidden" name="back" value="back">
                                        <input type="hidden"  value="'.$ur.'/" name="url" >
                                        <p class="text-left"">
                                        <button class="btn btn-danger" type="submit">ATRÁS</button>
                                        </p>        
                                    </form>
                                </li>
                                <li>
                                    <p class="text-right">Mostrando un total de '.$total.'</p>
                                </li>
                            </ul>
                        </div>';
            }
                
            
            
            if ($total>=1 && $pag<=$Npaginas) {
                $tabla.= mainmodel::paginador_tablas($pag,$Npaginas,$url,1);
            }

            return $tabla;


        }

        public function paginar_elimiM_controlador($pag,$registros,$url,$busqueda,$prod,$prove){
            $pag=mainModel::limpiar_cadena($pag);
            $registros=mainModel::limpiar_cadena($registros);

            $url=mainModel::limpiar_cadena($url);
            $ur=$url;
            $url=SERVERURL.$url."/";

            $busqueda=mainModel::limpiar_cadena($busqueda);
            $tabla="";
            
            $pag=(isset($pag) && $pag>0) ? (int) $pag : 1 ;
            $inicio=($pag>0) ? (($pag*$registros)-$registros) : 0 ;

            if (($prod!="" && $prove!="") && (isset($busqueda) && $busqueda!="")){
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM inventario where ((producto='$prod' and proveedor='$prove' and movimiento in (2)) and (id_producto LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%'))  ORDER BY fecha_ingreso asc LIMIT $inicio, $registros";
            }elseif ($prod!="" && $prove!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM inventario where producto='$prod' and proveedor='$prove' and movimiento in (2)  ORDER BY fecha_ingreso asc LIMIT $inicio, $registros";
            }elseif (isset($busqueda) && $busqueda!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) as cantidad FROM inventario WHERE ((movimiento in (2)) AND (id_producto LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%')) GROUP BY producto, proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
            }else {
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) AS cantidad FROM inventario WHERE movimiento in (2) GROUP BY producto,proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
            }
            
            $conexion=mainModel::conectar();
            /*
            $datos=mainModel::ejecutar_consulta_simple($consulta);

            $total=count($datos);*/
            $datos= $conexion->query($consulta);
            $datos=$datos->fetchAll();

            $total=$conexion-> query("SELECT FOUND_ROWS()");
            $total=(int) $total->fetchColumn();

            $Npaginas=ceil($total/$registros);

            if ($prod!="" && $prove!="") {
                $tabla.='<div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr class="text-center roboto-medium">
                                    <th>Id</th>
                                    <th>PRODUCTO</th>
                                    <th>PROVEEDOR</th>
                                    <th>SERIAL</th>
                                    <th>INVENTARIO</th>
                                    <th>FECHA INGRESO</th>
                                    <th>USR DIGITA</th>
                                    <th>MOVIMIENTO</th>
                                    <th>OBSERVACION</th>
                                </tr>
                            </thead>
                            <tbody>';
            } else {
                $tabla.='<div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr class="text-center roboto-medium">
                                    <th>#</th>
                                    <th>PRODUCTO</th>
                                    <th>PROVEEDOR</th>
                                    <th>CANTIDAD</th>
                                    <th>DETALLE</th>
                                </tr>
                            </thead>
                            <tbody>';
            }
            
            if ($total>=1 && $pag<=$Npaginas) {
                $contador=$inicio+1;
                $reg_i=$inicio+1;
                foreach($datos as $result) {
                    if ($prod!="" && $prove!="") {
                        $tabla.="<tr class='text-center' >
                                <td>".$result['id_producto']."</td>
                                <td>".$result['producto']."</td>
                                <td>".$result['proveedor']."</td>
                                <td>".$result['serial']."</td>
                                <td>".$result['inventario']."</td>
                                <td>".$result['fecha_ingreso']."</td>
                                <td>".$result['usr_digita']."</td>
                                <td>".$result['movimiento']."</td>
                                <td>
                                    <button type='submit' class='btn btn-info' data-toggle='popover' data-trigger='hover' title='Observacion' data-content='".$result['observacion']."'>
                                    <i class='fas fa-info-circle'></i>
                                    </button>
                                </td>
                                </tr>";
                    $contador++;
                    } else {
                        $tabla.="<tr class='text-center' >
                                <td>".$contador."</td>
                                <td>".$result['producto']."</td>
                                <td>".$result['proveedor']."</td>
                                <td>".$result['cantidad']."</td>
                                <td>
                                    <form action='".SERVERURL."ajax/buscaAjax.php' method='POST' >
                                        <input type='hidden' class='form-control' value='".$result['producto']."' name='producto' id='inputSearch' maxlength='30'>
                                        <input type='hidden' class='form-control' value='".$result['proveedor']."' name='proveedor' id='inputSearch' maxlength='30'>
                                        <input type='hidden'  value='".$ur."/' name='url' >
                                        <button type='submit' class='btn btn-info' data-toggle='popover' data-trigger='hover' title='Seriales e Inventarios' data-content='Fecha ingreso'>
                                            <i class='fas fa-info-circle'></i>
                                        </button>
                                    </form>    
                                </td>
                                </tr>";
                    $contador++;
                    }
                                   
                }
                $reg_f= $contador-1;
            } else {
                if ($total>=1) {
                    $tabla.='<tr class="text-center"><td colspan="7"><a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click para recargar el listado</a></td> </tr>';
                } else {
                    $tabla.='<tr class="text-center"><td colspan="9">No hay registros en el sistema</td> </tr>';
                }
            }
            


            $tabla.='</tbody></table></div>';
            
            if (($prod!="" && $prove!="") &&  $total>=1) {
                $tabla.='<div class="container-fluid">
                            <ul class="list-unstyled page-nav-tabs">
                                
                                <li>
                                    <form  action="'.SERVERURL.'ajax/buscaAjax.php" autocomplete="on" method="POST" data-form="save">
                                        <input type="hidden" name="back" value="back">
                                        <input type="hidden"  value="'.$ur.'/" name="url" >
                                        <p class="text-left"">
                                        <button class="btn btn-danger" type="submit">ATRÁS</button>
                                        </p>        
                                    </form>
                                </li>
                                <li>
                                    <p class="text-right">Mostrando '.$reg_i.' al '.$reg_f.' de un total de '.$total.'</p>
                                </li>
                            </ul>
                        </div>';
            }elseif($total>=1){
                $tabla.='<p class="text-right">Mostrando '.$reg_i.' al '.$reg_f.' de un total de '.$total.'</p>';
            }else{
                $tabla.='<div class="container-fluid">
                            <ul class="list-unstyled page-nav-tabs">
                                
                                <li>
                                    <form  action="'.SERVERURL.'ajax/buscaAjax.php" autocomplete="on" method="POST" data-form="save">
                                        <input type="hidden" name="back" value="back">
                                        <input type="hidden"  value="'.$ur.'/" name="url" >
                                        <p class="text-left"">
                                        <button class="btn btn-danger" type="submit">ATRÁS</button>
                                        </p>        
                                    </form>
                                </li>
                                <li>
                                    <p class="text-right">Mostrando un total de '.$total.'</p>
                                </li>
                            </ul>
                        </div>';
            }
                
            
            
            if ($total>=1 && $pag<=$Npaginas) {
                $tabla.= mainmodel::paginador_tablas($pag,$Npaginas,$url,1);
            }

            return $tabla;


        }

    }