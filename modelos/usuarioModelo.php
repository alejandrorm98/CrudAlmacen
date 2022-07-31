<?php
	
	require_once "mainModel.php";

	class usuarioModelo extends mainModel{

		/*--------- Modelo agregar usuario ---------*/
		protected static function agregar_usuario_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO hola (usr,pass,rol_usr,nombre_usr,apellido_usr,usr_modifica) VALUES(:Usuario,:Clave,:Privilegio,:Nombre,:Apellido,:usr_modifica)");

			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Apellido",$datos['Apellido']);
			$sql->bindParam(":Usuario",$datos['Usuario']);
			$sql->bindParam(":Clave",$datos['Clave']);
			$sql->bindParam(":Privilegio",$datos['Privilegio']);
			$sql->bindParam(":usr_modifica",$datos['usrC']);
			$sql->execute();

			return $sql;
		}

		public static function eliminar_usuario_modelo($datos){
			$sql=mainModel::conectar()->prepare("DELETE FROM hola WHERE usr=:usr and fecha_digita=:fecha_digita  and  usr_modifica=:usr_modifica;");
			
			$sql->bindParam(":usr",$datos['usr']);
			$sql->bindParam(":fecha_digita",$datos['fec']);
			$sql->bindParam(":usr_modifica",$datos['usrM']);

			$sql->execute();

			return $sql;
		}

		public function mostrar_contra($contra){
			return mainModel::decryption($contra);
		}


		public static function actuli_usuario_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE hola SET rol_usr=:rol_usr WHERE usr=:usr and fecha_digita=:fecha_digita  and  usr_modifica=:usr_modifica;");
			
			$sql->bindParam(":usr",$datos['usr']);
			$sql->bindParam(":fecha_digita",$datos['fec']);
			$sql->bindParam(":usr_modifica",$datos['usrM']);
			$sql->bindParam(":usr_modifica",$datos['usrM']);
			$sql->bindParam(":rol_usr",$datos['roll']);

			$sql->execute();

			return $sql;
		}
	}
	
