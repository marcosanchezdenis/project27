<?php
class Enc_QueryObject_Encuesta_Get extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->from("Model_EncParticular ep")
             ->leftJoin("ep.Encuesta e")
             ->leftJoin("ep.Asignatura a")
             
             ->leftJoin("ep.PeriodoLectivo pl")
             ->leftJoin("pl.Periodo pe")
             
             ->leftJoin("ep.Docente d")
             ->leftJoin("d.Persona p")
             
             ->where("ep.enc_particular_id = ?", $id);
    }
}