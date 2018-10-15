<?php
class Inf_QueryObject_Departamento_GetResultGen extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->from("Model_ResultadoDepartamento r")
			->where("r.resultado = ?", $id);
	}
}