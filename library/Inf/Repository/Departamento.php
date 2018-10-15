<?php
class Inf_Repository_Departamento extends Base_Repository
{
    public function getQueryObject($id)
    {
        return new Inf_QueryObject_Departamento_Get($id);
    }
    
    public function find()
    {
        $query = new Inf_QueryObject_Departamento_Listado();
        $departamentos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $departamentos;
    }
    
    public function getPeriodos($id)
    {
        $query = new Inf_QueryObject_Departamento_Informe($id);
        $periodos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $periodos;
    }
    
    public function findById($id)
    {
        $departamento = $this->findModelById($id);
        $periodos     = $this->getPeriodos($id);
        
        return array(
            'departamento_id' => $departamento->departamento_id,
            'departamento'    => $departamento->nombre,
            'codigo'          => $departamento->codigo,
            'facultad'        => $departamento->Facultad->nombre,
            'informes'        => $periodos,
        );
    }

	public function existGen($idEncPart)
    {
		$query = new Inf_QueryObject_Departamento_ExistGen($idEncPart);
		$data = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $data;
    }

	public function listResultsByEncPart($idEncPart)
	{
		$query = new Inf_QueryObject_Departamento_ListadoByEncPart($idEncPart);
		$res = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $res;
	}

	public function deleteGens($idEncPart)
	{
		$query = new Inf_QueryObject_Departamento_ListadoByEncPart($idEncPart);
		$results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

		$list = array();
		for($i=0; $i<count($results); $i++){
			$list[] = $results[$i]["resultado"];
		}

		$size = count($list);

		if($size > 0){
			$query = Doctrine_Query::create()
				->delete('Model_ResultadoDepartamento')
				->whereIn('resultado', $list);
			$query->execute();
		}
	}
}