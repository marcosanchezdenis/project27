<?php
class Enc_QueryObject_Encuesta_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
        	 ->addSelect("ep.enc_particular_id, ep.activo")
        	 ->addSelect("e.encuesta_id, e.nombre as encuesta")
        	 ->addSelect("d.docente_id, p.persona_id, (p.apellido || ', ' || p.nombre) as docente")
        	 ->addSelect("pl.periodo_lectivo_id, (pe.periodo || ' ' || pl.anho_lectivo) as periodo")
        	 ->addSelect("a.asignatura_id, a.nombre as asignatura")
        	 
        	 ->from("Model_EncParticular ep")
        	 ->leftJoin("ep.Encuesta e")
        	 ->leftJoin("ep.Asignatura a")
        	 
        	 ->leftJoin("ep.PeriodoLectivo pl")
        	 ->leftJoin("pl.Periodo pe")
        	 
        	 ->leftJoin("ep.Docente d")
        	 ->leftJoin("d.Persona p")
        	 
        	 ->orderBy("ep.enc_particular_id")
        	 ;
    }
}