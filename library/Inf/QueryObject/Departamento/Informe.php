<?php
class Inf_QueryObject_Departamento_Informe extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("ep.enc_particular_id, e.nombre as encuesta")
             ->addSelect("pl.periodo_lectivo_id as periodo_lectivo_id")
             ->addSelect("(p.periodo || ' ' || pl.anho_lectivo) as periodo")
             ->addSelect("(per.Nombre || ' ' || per.Apellido) as nomdoc")
             ->addSelect("a.nombre as nomasig")

             ->from("Model_EncParticular ep")
             ->innerJoin("ep.Docente d")
             ->innerJoin("d.Persona per")
             ->innerJoin("ep.Respuesta r")
             ->leftJoin("ep.Encuesta e")
             ->leftJoin("ep.PeriodoLectivo pl")
             ->leftJoin("pl.Periodo p")
             ->leftJoin("ep.Asignatura a")
             ->leftJoin("a.Carrera c")
             
             ->where("c.departamento_id = ?", $id)
             ->orderBy("periodo, a.nombre");
    }
}
