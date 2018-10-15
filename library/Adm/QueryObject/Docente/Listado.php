<?php
class Adm_QueryObject_Docente_Listado extends Base_QueryObject
{
	public function __construct()
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->addSelect("d.docente_id, d.codigo, d.activo")
			 ->addSelect("p.persona_id, p.nombre, p.apellido, p.email")
			 
			 ->from("Model_Docente d")
			 ->leftJoin("d.Persona p")
			 ->leftJoin("p.Usuario u")
		
		     ->orderBy("d.codigo");
	}
}