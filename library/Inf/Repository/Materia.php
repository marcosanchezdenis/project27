<?php
class Inf_Repository_Materia extends Base_Repository
{

	public function getListEncuestas($idAsignatura, $idPeriodoLectivo)
	{
		$query = new Inf_QueryObject_Materia_ListEncuestas($idAsignatura, $idPeriodoLectivo);
		$encuestas = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $encuestas;
	}

	public function existGen($idMateria, $idPerLec)
	{
		$query = new Inf_QueryObject_Materia_ExistGen($idMateria, $idPerLec);
		$data = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $data;
	}

	public function listResultsByEncPart($idMateria, $idPerLec)
	{
		$query = new Inf_QueryObject_Materia_ListadoByEncPart($idMateria, $idPerLec);
		$res = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $res;
	}

	public function deleteGens($idMateria, $idPerLec)
	{
		$query = new Inf_QueryObject_Materia_ListadoByEncPart($idMateria, $idPerLec);
		$results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

		$list = array();
		for($i=0; $i<count($results); $i++){
			$list[] = $results[$i]["resultado"];
		}

		$size = count($list);

		if($size > 0){
			$query = Doctrine_Query::create()
				->delete('Model_ResultadoMateria')
				->whereIn('resultado', $list);
			$query->execute();
		}
	}
}