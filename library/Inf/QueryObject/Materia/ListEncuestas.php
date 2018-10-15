<?php
class Inf_QueryObject_Materia_ListEncuestas extends Base_QueryObject
{
	public function __construct($idAsignatura, $idPerLect)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->distinct(true)
			->addSelect("ep.enc_particular_id")

			->from("Model_EncParticular ep")
			->innerJoin("ep.Respuesta r")
			->leftJoin("ep.Asignatura a")
			->leftJoin("ep.PeriodoLectivo pl")

			->where("a.asignatura_id = ?", $idAsignatura)
			->addWhere("pl.periodo_lectivo_id = ?", $idPerLect);
	}
}