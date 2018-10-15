<?php
class Adm_QueryObject_Rol_Listado extends Base_QueryObject
{
	public function __construct()
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->addSelect("r.rol_id, r.nombre")
			 ->from("Model_Rol r")
			 ->orderBy("r.nombre DESC");
	}
}