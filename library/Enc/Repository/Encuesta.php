<?php
class Enc_Repository_Encuesta extends Base_Repository
{
    public function getQueryObject($id)
    {
        return new Enc_QueryObject_Encuesta_Get($id);
    }
    
    public function find($searchParams = null)
    {
        $query = new Enc_QueryObject_Encuesta_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }

	public function findFiltered($perLec, $asignatura, $docente)
	{
		$query = new Enc_QueryObject_Encuesta_ListadoFiltrado($perLec, $asignatura, $docente);
		$results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $results;
	}
    
    public function findById($id)
    {
        $encuesta = $this->findModelById($id);
        
        if ($encuesta == null) {
            throw new Exception("No se encontro la encuesta con id = $id");
        }
        
        $docente  = $encuesta->Docente;
        $nombre   = $docente->Persona->nombre;
        $apellido = $docente->Persona->apellido;
        
        $anho_lectivo = $encuesta->PeriodoLectivo->anho_lectivo;
        $periodo_lectivo = $encuesta->PeriodoLectivo->Periodo->periodo;
        
        return array(
            "enc_particular_id" => $encuesta->enc_particular_id,
            "encuesta_id"       => $encuesta->encuesta_id,
            "encuesta"          => $encuesta->Encuesta->nombre,
            "docente_id"        => $encuesta->docente_id,
            "docente"           => $apellido . ", " . $nombre,
            "asignatura_id"     => $encuesta->asignatura_id,
            "asignatura"        => $encuesta->Asignatura->nombre,
            "periodo_lectivo_id"=> $encuesta->periodo_lectivo_id,
            "periodo"           => $periodo_lectivo . " " . $anho_lectivo,
            "identificador"     => trim($encuesta->identificador),
            "activo"            => $encuesta->activo,
            "password"          => $encuesta->password,
            "fecha_inicio"      => $encuesta->fecha_inicio,
            "fecha_fin"         => $encuesta->fecha_fin,
            "max_resp"          => $encuesta->max_resp
        );
    }
    
    public function getPlantillas()
    {
        $query = new Enc_QueryObject_Plantilla_Habilitadas();
        $plantillas = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $plantillas;
    }
    
    public function getPeriodos()
    {
        $query = new Enc_QueryObject_Periodo_Habilitados();
        $periodos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $periodos;
    }
    
    public function getDocentes()
    {
        $query = new Adm_QueryObject_Docente_Habilitados();
        $docentes = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $docentes;
    }
    
    public function getAsignaturas()
    {
        $query = new Adm_QueryObject_Asignatura_Habilitadas();
        $asignaturas = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $asignaturas;
    }
}