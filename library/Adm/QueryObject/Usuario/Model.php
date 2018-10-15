<?php
class Adm_QueryObject_Usuario_Model extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->from("Model_Usuario u")
			 ->where("u.usuario_id = ?", $id);
	}
}