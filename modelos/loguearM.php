<?php
	
	require_once "mainModel.php";

	class loguearM extends mainModel{

		/*--------- Modelo agregar usuario ---------*/
		protected static function loguear_modelo($datos){
            $usr=$datos['usr'];
            $clave=mainModel::encryption($datos['clave']);
			$q="SELECT COUNT(*) as contar,rol_usr FROM hola where usr='$usr' and pass='$clave'";
            $sql=mainModel::ejecutar_consulta_simple($q);
            
            if ($sql[0]['contar']>0) {
                $_SESSION['username']=$datos['usr'];
                $_SESSION['roll']=$sql[0]['rol_usr'];
                header("Location: ".SERVERURL."home/");
    
            } else {
                header("Location: ".SERVERURL."");
            }

			
		}

	}
