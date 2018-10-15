<?php
class Inf_QueryObject_Materia_GetResultGen extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->from("Model_ResultadoMateria r")
			->where("r.resultado = ?", $id);
	}
}