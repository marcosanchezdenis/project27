<?php
class Inf_QueryObject_Departamento_ExistGen extends Base_QueryObject
{
	public function __construct($idEncPart)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->addSelect("count(*) as cant")
			->from("Model_ResultadoDepartamento r")
			->where("r.enc_particular_id = ?", $idEncPart);
	}
}