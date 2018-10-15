<?php
class Inf_QueryObject_Materia_ExistGen extends Base_QueryObject
{
	public function __construct($idMateria, $idPerLec)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->addSelect("count(*) as cant")
			->from("Model_ResultadoMateria r")
			->where("r.asignatura_id = ?", $idMateria)
			->andWhere("r.periodo_lectivo_id = ?", $idPerLec);
	}
}