<?php
class Ins_QueryObject_Departamento_Get extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->from("Model_Departamento d")
			 ->where("d.departamento_id = ?", $id);
	}
}