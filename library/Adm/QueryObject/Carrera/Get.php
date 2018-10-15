<?php
class Adm_QueryObject_Carrera_Get extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->from("Model_Carrera c")
			 ->where("c.carrera_id = ?", $id);
	}
}