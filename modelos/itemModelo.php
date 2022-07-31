<?php
	
	require_once "mainModel.php";

   

	class itemModelo extends mainModel{

		/*--------- Modelo agregar usuario ---------*/
		public static function agregar_item_modelo($datos){

			$values='';

			for ($i=0; $i <= $datos[0]['cant']; $i++) { 
				$values.='("'.$datos[$i]['producto'].'","'.$datos[$i]['proveedor'].'","'.$datos[$i]['serial'].'","'.$datos[$i]['inventario'].'",null,null,null,null,null,null,"'.$datos[$i]['usr'].'"),';
			}

			$values= substr($values,0,-1);

			$q="INSERT INTO inventario (producto,proveedor, serial, inventario, fecha_salida, destino, recibe, observacion, fecha_modifica, usr_modifica,usr_digita) VALUES $values ;";

			$sql=mainModel::conectar()->prepare($q);
			
			$sql->execute();

			return $sql;
		}

		public static function eliminar_item_modelo($datos){
			$id=$datos["id"];
			$q="SELECT * FROM inventario where id_producto= $id and movimiento in (0,2);";
			
			$sqls=mainModel::ejecutar_consulta_simple($q);

			if ($sqls[0]['movimiento'] == 2) {
				$sqls[0]['movimiento']=3;
			}
			
			$sql1=mainModel::conectar()->prepare("INSERT INTO eliminado(id_producto, producto,proveedor, serial, inventario, usr_elimina,  observacion,movimiento,fecha_ingreso,usr_digita) VALUES (:id,:producto,:proveedor,:serial1,:inventario,:usr_elimina,:obs,:movimiento,:fecha,:usr_digita);");

			$sql1->bindParam(":id",$sqls[0]['id_producto']);
			$sql1->bindParam(":producto",$sqls[0]['producto']);
			$sql1->bindParam(":proveedor",$sqls[0]['proveedor']);
			$sql1->bindParam(":serial1",$sqls[0]['serial']);
			$sql1->bindParam(":inventario",$sqls[0]['inventario']);
			$sql1->bindParam(":usr_elimina",$_SESSION['username']);
			$sql1->bindParam(":obs",$datos['obs']);
			$sql1->bindParam(":movimiento",$sqls[0]['movimiento']);
			$sql1->bindParam(":fecha",$sqls[0]['fecha_ingreso']);
			$sql1->bindParam(":usr_digita",$sqls[0]['usr_digita']);
			$sql1->execute();

			$sql=mainModel::conectar()->prepare("DELETE FROM inventario WHERE id_producto=:id and proveedor=:proveedor  and  inventario=:inventario;");
			
			$sql->bindParam(":id",$datos['id']);
			$sql->bindParam(":proveedor",$datos['proveedor']);
			$sql->bindParam(":inventario",$datos['inventario']);

			$sql->execute();

			return $sql;
		}
		
		public function listar_item_modelo($prod,$prove){

            $q="SELECT producto,proveedor,serial,inventario, fecha_ingreso  FROM inventario where producto=$prod and proveedor=$prove and movimiento in (0,2) GROUP BY proveedor ORDER BY producto, cantidad DESC";
			$sql=mainModel::ejecutar_consulta_simple($q);

			return $sql;

        }

		

	}

		/*$q="SELECT * FROM inventario where id_producto= 310 and movimiento in (0,2);";
			
		$sqls=mainModel::ejecutar_consulta_simple($q);
	
		print_r($sqls);
		print('/n'.$sqls[0]['id_producto'])*/

?>