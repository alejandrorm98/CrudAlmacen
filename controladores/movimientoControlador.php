<?php
    
	if($peticionAjax){
		require_once "../modelos/movimientoModelo.php";
	}else{
		require_once "./modelos/movimientoModelo.php";
	}
    /*
    require_once "../modelos/movimientoModelo.php";
   */
    
    
	class movimientoControlador extends movimientoModelo{
        
		/*--------- Controlador agregar usuario ---------*/
		public function agregar_movimiento_controlador(){
            $producto=mainModel::limpiar_cadena($_POST['producto']);
			$proveedor=mainModel::limpiar_cadena($_POST['proveedor']);
			$destino=mainModel::limpiar_cadena($_POST['destino']);
			$recibe=mainModel::limpiar_cadena($_POST['recibe']);
            $cantidad=mainModel::limpiar_cadena($_POST['cantidad']);
			$observacion=mainModel::limpiar_cadena($_POST['observacion']);

            $datos=[
                "producto"=>$producto,
                "proveedor"=>$proveedor,
                "destino"=>$destino,
                "recibe"=>$recibe,
                "cantidad"=>$cantidad,
                "observacion"=>$observacion,
                "usr"=>$_SESSION['username']
            ];

            $consulta="SELECT SQL_CALC_FOUND_ROWS COUNT(proveedor) AS cantidad FROM inventario WHERE movimiento in (0,2) and producto='$producto' and proveedor='$proveedor' GROUP BY producto,proveedor ORDER BY fecha_ingreso asc";

            $cant=mainModel::ejecutar_consulta_simple($consulta);
            
            if ($cant[0]['cantidad']>=$cantidad){
                $sql=movimientoModelo::agregar_movimiento_modelo($datos);
            
                
                if($sql->rowCount() > 0){
                        $count = $sql -> rowCount();
                        $alerta=[
                            "Alerta"=>"limpiar",
                            "Titulo"=>"Bien",
                            "Texto"=>"Bien",
                            "Tipo"=>"success"
                        ];
                    }else{
                        $alerta=[
                            'Alerta'=>"simple",
                            'Titulo'=>"Error",
                            'Texto'=>"No se cargaron los datos: ".$sql->errorInfo(),
                            'Tipo'=>"error"
                        ]; 
                        /*$alerta=$sql->errorInfo(); */
                }
                
                return json_encode($alerta);
            }else{
                $alerta=[
                    'Alerta'=>"simple",
                    'Titulo'=>"Error",
                    'Texto'=>"valide la cantidad, ese producto solo tiene ".$cant[0]['cantidad']." unidades",
                    'Tipo'=>"warning"
                ];
                return json_encode($alerta);
            }
        
        

            
        }

        

        public function listar_movimiento_controlador(){

            $datos=movimientoModelo::listar_movimiento_modelo();

            return $datos;  

        }


                /*eliminar movimientos*/
        public function eliminar_movimiento_controlador(){
            $id=mainModel::limpiar_cadena($_POST['id']);
			$producto=mainModel::limpiar_cadena($_POST['producto']);
			$proveedor=mainModel::limpiar_cadena($_POST['proveedor']);
			$inventario=mainModel::limpiar_cadena($_POST['inventario']);
            $obs=mainModel::limpiar_cadena($_SESSION['obs']);

            $datos=[
                "producto"=>$id,
                "proveedor"=>$proveedor,
                "inventario"=>$inventario,
                "obs"=>$obs
            ];

            $sql=movimientoModelo::eliminar_movimiento_modelo($datos);
            
            unset($_POST['obs']);

            if($sql->rowCount() > 0){
                $count = $sql -> rowCount();
                $alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Bien",
					"Texto"=>"El producto: ".$producto." de proveedor: ".$proveedor." con serial: ".$inventario." esta disponible nuevamente",
					"Tipo"=>"success"
				];
            }else{
                $alerta=[
					'Alerta'=>"simple",
					'Titulo'=>"Error",
					'Texto'=>"No se eliminaron los datos",
					'Tipo'=>"error"
				]; 
               /* $alerta=$sql->errorInfo(); */
             }
            

            return json_encode($alerta);  

        }

        public function select_movimiento_controlador($prod){

            $prod=mainModel::limpiar_cadena($prod);
            if ($prod ==='') {
                $consulta="SELECT DISTINCT SQL_CALC_FOUND_ROWS producto FROM inventario where movimiento in (0,2) ORDER BY producto ASC";
            } else {
                $consulta="SELECT DISTINCT SQL_CALC_FOUND_ROWS proveedor FROM inventario where movimiento in (0,2) and producto='$prod' ORDER BY producto ASC";
            }
            
            $datos=mainModel::ejecutar_consulta_simple($consulta);
            
            if ($prod ==='') {
                $cadena='';

                foreach($datos as $result) {
                    $cadena.='<option value='.$result['producto'].'>'.utf8_encode($result['producto']).'</option>';
                }

                return $cadena; 
            }else{
                $cadena='<select class="form-control" name="proveedor" required="">
                <option value="" selected="" disabled="" name="producto">Seleccione un Proveedor</option>';

                foreach($datos as $result) {
                    $cadena.='<option value='.$result['proveedor'].'>'.utf8_encode($result['proveedor']).'</option>';
                }
                
                return $cadena."</select>";
            }   

        }

        /*--------- Controlador agregar usuario ---------*/
		public function paginar_movimiento_controlador($pag,$registros,$url,$busqueda,$prod,$prove){
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
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto, producto ,proveedor,serial,inventario, fecha_ingreso, fecha_salida, destino,recibe,observacion,usr_modifica  FROM inventario where ((producto='$prod' and proveedor='$prove' and movimiento=1) and (id_producto LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%' OR destino LIKE '%$busqueda%' OR recibe LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%'))  ORDER BY fecha_salida DESC LIMIT $inicio, $registros";
            }elseif ($prod!="" && $prove!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto, producto ,proveedor,serial,inventario, fecha_ingreso, fecha_salida, destino,recibe,observacion,usr_modifica  FROM inventario where producto='$prod' and proveedor='$prove' and movimiento=1  ORDER BY fecha_salida DESC LIMIT $inicio, $registros";
            }elseif (isset($busqueda) && $busqueda!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) as cantidad FROM inventario WHERE ((movimiento= 1) AND (id_producto LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%' OR destino LIKE '%$busqueda%' OR recibe LIKE '%$busqueda%')) GROUP BY producto, proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
            }else {
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto, proveedor, COUNT(proveedor) AS cantidad FROM inventario WHERE movimiento= 1 GROUP BY producto,proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
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
                                    <th>ID</th>
                                    <th>PRODUCTO</th>
                                    <th>PROVEEDOR</th>
                                    <th>SERIAL</th>
                                    <th>INVENTARIO</th>
                                    <th>FECHA INGRESO</th>
                                    <th>FECHA SALIDA</th>
                                    <th>DESTINO</th>
                                    <th>RECIBE</th>
                                    <th>USR MODIFICA</th>
                                    <th>OBSERVACION</th>
                                    <th>REGRESAR</th>
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
                                <td>".$result['fecha_salida']."</td>
                                <td>".$result['destino']."</td>
                                <td>".$result['recibe']."</td>
                                <td>".$result['usr_modifica']."</td>
                                <td>
                                    <button type='submit' class='btn btn-info' data-toggle='popover' data-trigger='hover' title='Observacion' data-content='".$result['observacion']."'>
                                    <i class='fas fa-info-circle'></i>
                                    </button>
                                </td>
                                <td>  
                                    <form action='".SERVERURL."ajax/elimAjax.php' method='POST' class='FormularioAjax' data-form='delete' value='".$contador."-".$result['serial']."'>
                                        <input type='hidden'  value='".$result['id_producto']."' name='id' >
                                        <input type='hidden'  value='".$result['producto']."' name='producto' >
                                        <input type='hidden'  value='".$result['proveedor']."' name='proveedor' >
                                        <input type='hidden'  value='".$result['inventario']."' name='inventario' >
                                        <input type='hidden'  value='".$ur."/' name='url' >
                                        <button type='submit' class='btn btn-success'>
                                            <i class='fas fa-sync-alt'></i>
                                        </button>
                                    </form>
                                </td>
                        </tr>";
                    $contador++;
                    }  else {
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
                    $tabla.='<tr class="text-center"><td colspan="12">No hay registros en el sistema</td> </tr>';
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

        /*
        public function buscar_movimiento_controlador($pag,$registros,$url){
            echo 'holas';
            if (isset($_POST['busqueda'])) {
                return movimientoControlador::paginar_movimiento_controlador($pag,$registros,$url,$_POST['busqueda']);
            } else {
                return movimientoControlador::paginar_movimiento_controlador($pag,$registros,$url,"");
            }
            
            
        }*/

        
    }


    

    /*
    print_r(movimientoControlador::listar_movimiento_controlador());
    $results=movimientoControlador::listar_movimiento_controlador();
    echo '<br>'.$results[0]['producto'];
    
    $results=movimientoControlador::listar_movimiento_controlador();
    $contador=0;
    // Usaremos el ciclo para mostrar resultados
    foreach($results as $result) {
        echo "<tr class='text-center' >";
        echo    "<td>".$contador."</td>
                <td>".$result['producto']."</td>
                <td>".$result['proveedor']."</td>
                <td>".$result['cantidad']."</td>
            </tr><br>";
            $contador++;
    }*/