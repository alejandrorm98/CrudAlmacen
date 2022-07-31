<?php

require_once "../modelos/loguearM.php";

class loguear extends loguearM{

    public function loguear_controlador(){
        $usr=mainModel::limpiar_cadena($_POST['usuario']);
		$clave=mainModel::limpiar_cadena($_POST['clave']);

        $datos=[
            "usr"=>$usr,
            "clave"=>$clave
        ];
        
        loguearM::loguear_modelo($datos);
    } 
} 

?>  