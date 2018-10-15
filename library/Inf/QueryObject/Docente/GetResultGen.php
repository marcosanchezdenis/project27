<?php
class Inf_QueryObject_Docente_GetResultGen extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->from("Model_ResultadoDocente r")
			->where("r.resultado = ?", $id);
	}
}