<?php
class Inf_Repository_Docente extends Base_Repository
{
    public function getQueryObject($id)
    {
        return new Inf_QueryObject_Docente_Get($id);
    }
    
    public function find()
    {
        $query = new Inf_QueryObject_Docente_Listado();
        $docentes = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $docentes;
    }
    
    public function getInformes($id)
    {
        $query = new Inf_QueryObject_Docente_Informe($id);
        $periodos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $periodos;
    }

	public function getInformesRestricted($id)
	{
		$query = new Inf_QueryObject_Docente_InformeRestricted($id);
		$periodos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $periodos;
	}
    
    public function findById($id)
    {
        $docente  = $this->findModelById($id);
        $informes = $this->getInformes($id);
        
        return array(
            'docente_id' => $docente->docente_id,
            'codigo'     => $docente->codigo,
            'docente'    => $docente->getNombre(),
            'email'      => $docente->Persona->email,
            'activo'     => $docente->activo,
            'informes'   => $informes,
        );
    }

	public function findByIdRestricted($id)
	{
		$docente  = $this->findModelById($id);
		$informes = $this->getInformesRestricted($id);

		return array(
			'docente_id' => $docente->docente_id,
			'codigo'     => $docente->codigo,
			'docente'    => $docente->getNombre(),
			'email'      => $docente->Persona->email,
			'activo'     => $docente->activo,
			'informes'   => $informes,
		);
	}

	public function existGen($idEncPart)
	{
		$query = new Inf_QueryObject_Docente_ExistGen($idEncPart);
		$data = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $data;
	}

	public function listResultsByEncPart($idEncPart)
	{
		$query = new Inf_QueryObject_Docente_ListadoByEncPart($idEncPart);
		$res = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $res;
	}

	public function deleteGens($idEncPart)
	{
		$query = new Inf_QueryObject_Docente_ListadoByEncPart($idEncPart);
		$results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

		$list = array();
		for($i=0; $i<count($results); $i++){
			$list[] = $results[$i]["resultado"];
		}

		$size = count($list);

		if($size > 0){
			$query = Doctrine_Query::create()
				->delete('Model_ResultadoDocente')
				->whereIn('resultado', $list);
			$query->execute();
		}
	}
}