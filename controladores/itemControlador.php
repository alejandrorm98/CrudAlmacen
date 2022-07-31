<?php

	if($peticionAjax){
		require_once "../modelos/itemModelo.php";
	}else{
		require_once "./modelos/itemModelo.php";
	}
      /*
    require_once "../modelos/itemModelo.php";
    */
    
	class itemControlador extends itemModelo{
        
		/*--------- Controlador agregar usuario ---------*/
		public function agregar_item_controlador(){
            $producto=mainModel::limpiar_cadena($_POST['producto']);
			$proveedor=mainModel::limpiar_cadena($_POST['proveedor']);
            $cant=mainModel::limpiar_cadena($_POST['cantidad']);
            
            $datos=[];

            for ($i=0; $i <= $cant; $i++) { 
                
                array_push($datos,["producto"=>$producto,"proveedor"=>$proveedor,"serial"=>mainModel::limpiar_cadena($_POST['serial'.$i.'']),"inventario"=>mainModel::limpiar_cadena($_POST['inventario'.$i.'']),"usr"=>$_SESSION['username'],"cant"=>$cant]);

            }
            
            $r=itemModelo::agregar_item_modelo($datos);
            
            
            if($r){
                $alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Bien",
					"Texto"=>"Bien",
					"Tipo"=>"success"
				];
                
            }else{
                $alerta=[
					'Alerta'=>"simple",
					'Titulo'=>"Error",
					'Texto'=>"No se cargaron los datos",
					'Tipo'=>"error"
				];
                
            }
            
            return json_encode($alerta);
        }

        public function listar_item_controlador(){

            $datos=itemModelo::listar_item_modelo();

            return $datos;  

        }
                /*eliminar items*/
        public function eliminar_item_controlador(){
            $producto=mainModel::limpiar_cadena($_POST['producto']);
            $id=mainModel::limpiar_cadena($_POST['id']);
			$proveedor=mainModel::limpiar_cadena($_POST['proveedor']);
			$inventario=mainModel::limpiar_cadena($_POST['inventario']);
            $obs=mainModel::limpiar_cadena($_SESSION['obs']);

            $datos=[
                "id"=>$id,
                "proveedor"=>$proveedor,
                "inventario"=>$inventario,
                "obs"=>$obs
            ];

            $sql=itemModelo::eliminar_item_modelo($datos);

            if($sql->rowCount() > 0){
                $count = $sql -> rowCount();
                $alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Bien",
					"Texto"=>"El producto: ".$producto." de proveedor: ".$proveedor." con serial: ".$inventario." se elimino correctamente",
					"Tipo"=>"success"
				];
            }else{
                $alerta=[
					'Alerta'=>"simple",
					'Titulo'=>"Error",
					'Texto'=>"No se eliminaron los datos",
					'Tipo'=>"error"
				]; 
                //$alerta=$sql->errorInfo(); 
             }


            return json_encode($alerta);  

        }

        /*--------- Controlador agregar usuario ---------*/
		public function paginar_item_controlador($pag,$registros,$url,$busqueda,$prod,$prove){
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
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM inventario where ((producto='$prod' and proveedor='$prove' and movimiento in (0,2)) and (id_producto LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%'))  ORDER BY fecha_ingreso asc LIMIT $inicio, $registros";
            }elseif ($prod!="" && $prove!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM inventario where producto='$prod' and proveedor='$prove' and movimiento in (0,2)  ORDER BY fecha_ingreso asc LIMIT $inicio, $registros";
            }elseif (isset($busqueda) && $busqueda!=""){
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) as cantidad FROM inventario WHERE ((movimiento in (0,2)) AND (id_producto LIKE '%$busqueda%' OR producto LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%' OR serial LIKE '%$busqueda%' OR inventario LIKE '%$busqueda%')) GROUP BY producto, proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
            }else {
                $consulta="SELECT SQL_CALC_FOUND_ROWS id_producto,producto,proveedor,COUNT(proveedor) AS cantidad FROM inventario WHERE movimiento in (0,2) GROUP BY producto,proveedor ORDER BY producto, cantidad DESC LIMIT $inicio, $registros";
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
                                    <th>ELIMINAR</th>
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
                                <td>  
                                    <form action='".SERVERURL."ajax/elimAjax.php' method='POST' class='FormularioAjax' data-form='delete' value='".$contador."-".$result['serial']."'>
                                        <input type='hidden'  value='".$result['id_producto']."' name='id' >
                                        <input type='hidden'  value='".$result['producto']."' name='producto' >
                                        <input type='hidden'  value='".$result['proveedor']."' name='proveedor' >
                                        <input type='hidden'  value='".$result['inventario']."' name='inventario' >
                                        <input type='hidden'  value='".$ur."/' name='url' >
                                        <button type='submit' class='btn btn-warning'>
                                            <i class='far fa-trash-alt'></i>
                                        </button>
                                    </form>
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
                    $tabla.='<tr class="text-center"><td colspan="10">No hay registros en el sistema</td> </tr>';
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

        public function select_item_controlador($prod){

            $prod=mainModel::limpiar_cadena($prod);
            $prod=intval($prod);
            
            $cadena='';

            for ($i=1; $i <= $prod; $i++) { 
                $cadena.='
                            <div class="form-group">
                                <label for="item_nombre" class="bmd-label-floating">Inventario '. $i .'</label>
                                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-]{1,150}" class="form-control" name="inventario'. $i .'" id="item_nombre" maxlength="140" required="" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                            </div>
                            
                        
                            <div class="form-group">
                                <label for="item_stock" class="bmd-label-floating">Serial '. $i .'</label>
                                <input type="num" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9().,#\-]{1,140}" class="form-control" name="serial'. $i .'" id="item_stock" maxlength="140" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" maxlength="9" required="">
                            </div>
                            ';
            }
            
            return $cadena; 
        }

    

        /*
        public function buscar_item_controlador($pag,$registros,$url){
            echo 'holas';
            if (isset($_POST['busqueda'])) {
                return itemControlador::paginar_item_controlador($pag,$registros,$url,$_POST['busqueda']);
            } else {
                return itemControlador::paginar_item_controlador($pag,$registros,$url,"");
            }
            
            
        }*/

    }
    
    /*
    $s=[];

    for ($i=0; $i < 5; $i++) { 
        array_push($s,["producto$i"=>$i,"proveedor"=>$i,"serial"=>$i]);
    }
   
    print_r($s);*/
   
    /*
    print_r(itemControlador::listar_item_controlador());
    $results=itemControlador::listar_item_controlador();
    echo '<br>'.$results[0]['producto'];
    
    $results=itemControlador::listar_item_controlador();
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