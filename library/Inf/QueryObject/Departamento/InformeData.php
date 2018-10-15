<?php
class Inf_QueryObject_Departamento_InformeData extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("(ep.enc_particular_id) as id")
             ->addSelect("(e.nombre) as plantilla")
             ->addSelect("(p.periodo || ' ' || pl.anho_lectivo) as periodo")
             ->addSelect("d.docente_id as docente_id")
             ->addSelect("(per.nombre || ' ' || per.apellido) as docente")
             ->addSelect("(a.nombre) as asignatura")
             ->addSelect("dep.departamento_id as departamento_id")
             ->addSelect("(dep.nombre || ' (' || dep.codigo || ')') as departamento")
             ->addSelect("count(r.respuesta_id) as cnt_respuestas")
             
             ->from("Model_EncParticular ep")
             ->innerJoin("ep.Respuesta r")
             ->leftJoin("ep.Encuesta e")
             
             ->leftJoin("ep.PeriodoLectivo pl")
             ->leftJoin("pl.Periodo p")
             
             ->leftJoin("ep.Docente d")
             ->leftJoin("d.Persona per")
             
             ->leftJoin("ep.Asignatura a")
             ->leftJoin("a.Carrera c")
             ->leftJoin("c.Departamento dep")
             
             ->where("ep.enc_particular_id = ?", $id)
             
             ->addGroupBy("ep.enc_particular_id, e.nombre, p.periodo, pl.anho_lectivo")
             ->addGroupBy("d.docente_id, per.nombre, per.apellido, a.nombre")
             ->addGroupBy("dep.departamento_id, dep.nombre, dep.codigo");
    }
}