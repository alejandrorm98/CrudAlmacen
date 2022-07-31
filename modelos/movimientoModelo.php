<?php
	
	require_once "mainModel.php";
	
   

	class movimientoModelo extends mainModel{

		/*--------- Modelo agregar usuario ---------*/
		public static function agregar_movimiento_modelo($datos){
			$cant=intval($datos['cantidad']);
			
			$sql=mainModel::conectar()->prepare("UPDATE inventario SET movimiento = 1, fecha_salida=CURRENT_TIMESTAMP() , destino=:destino , recibe=:recibe, usr_modifica=:usr , observacion=:observacion WHERE  movimiento in (0,2) AND producto=:producto and proveedor=:proveedor ORDER BY fecha_ingreso asc LIMIT $cant;");

			$sql->bindParam(":producto",$datos['producto']);
			$sql->bindParam(":proveedor",$datos['proveedor']);
			$sql->bindParam(":destino",$datos['destino']);
			$sql->bindParam(":recibe",$datos['recibe']);
			$sql->bindParam(":observacion",$datos['observacion']);
			$sql->bindParam(":usr",$datos['usr']);
			$sql->execute();

			return $sql;
		}

		public static function eliminar_movimiento_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE inventario SET movimiento = 2, fecha_salida=NULL , destino='' , recibe='',  observacion=:obs WHERE id_producto=:producto and proveedor=:proveedor and  inventario=:inventario ;");
			
			$sql->bindParam(":producto",$datos['producto']);
			$sql->bindParam(":proveedor",$datos['proveedor']);
			$sql->bindParam(":inventario",$datos['inventario']);
			$sql->bindParam(":obs",$datos['obs']);

			$sql->execute();

			return $sql;
		}
		
		public function listar_movimiento_modelo($prod,$prove){

            $q="SELECT producto,proveedor,serial,inventario, fecha_ingreso  FROM inventario where producto=$prod and proveedor=$prove and movimiento=0 GROUP BY proveedor ORDER BY producto, cantidad DESC";
			$sql=mainModel::ejecutar_consulta_simple($q);

			return $sql;

        }

	}

?>