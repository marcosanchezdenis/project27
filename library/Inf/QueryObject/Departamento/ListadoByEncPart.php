<?php
class Inf_QueryObject_Departamento_ListadoByEncPart extends Base_QueryObject
{
	public function __construct($idEncPart)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->from("Model_ResultadoDepartamento as r")
			->where("r.enc_particular_id = ?", $idEncPart);
	}
}
