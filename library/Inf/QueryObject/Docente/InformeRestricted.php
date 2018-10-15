<?php
class Inf_QueryObject_Docente_InformeRestricted extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->distinct(true)
			->addSelect("ep.enc_particular_id")
			->addSelect("a.asignatura_id, a.nombre as asignatura")
			->addSelect("pl.periodo_lectivo_id as periodo_lectivo_id")
			->addSelect("(p.periodo || ' ' || pl.anho_lectivo) as periodo")
			->addSelect("c.carrera_id, d.departamento_id")
			->addSelect("d.codigo as departamento")

			->from("Model_EncParticular ep")
			->innerJoin("ep.Respuesta r")
			->leftJoin("ep.Asignatura a")
			->leftJoin("a.Carrera c")
			->leftJoin("c.Departamento d")
			->leftJoin("ep.PeriodoLectivo pl")
			->leftJoin("pl.Periodo p")

			->where("ep.docente_id = ?", $id)
			->andWhere("pl.visible_profesores = 'S'")
			->orderBy("periodo, a.nombre");
	}
}