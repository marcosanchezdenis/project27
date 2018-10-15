<?php
class Inf_QueryObject_Materia_ListadoByEncPart extends Base_QueryObject
{
	public function __construct($idMateria, $idPerLec)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->from("Model_ResultadoMateria as r")
			->where("r.asignatura_id = ?", $idMateria)
			->andWhere("r.periodo_lectivo_id = ?", $idPerLec);
	}
}
