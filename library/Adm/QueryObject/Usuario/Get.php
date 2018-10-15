<?php
class Adm_QueryObject_Usuario_Get extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
		     ->addSelect("u.usuario_id, u.usuario, u.activo")
		     ->addSelect("p.persona_id, p.apellido, p.nombre, p.email")
		     ->addSelect("r.rol_id, r.nombre as rol")
		     
			 ->from("Model_Usuario u")
			 ->leftJoin("u.Persona p")
			 
			 ->leftJoin("u.UsuarioPerm up")
			 ->leftJoin("up.Rol r")
			 
			 ->where("u.usuario_id = ?", $id);
	}
}