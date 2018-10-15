<?php
class Adm_QueryObject_Usuario_Listado extends Base_QueryObject
{
	public function __construct()
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->addSelect("u.usuario_id, u.usuario, u.activo")
			 ->addSelect("p.persona_id, p.nombre, p.apellido, p.email")
			 
			 ->from("Model_Usuario u")
			 ->leftJoin("u.Persona p")
		
		     ->orderBy("u.usuario");
	}
}