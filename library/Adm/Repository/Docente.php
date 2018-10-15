<?php
class Adm_Repository_Docente extends Base_Repository
{
    protected function getFindQueryObject()
    {
        return new Adm_QueryObject_Docente_Listado();
    }
    
	/**
	 * @return Model_Docente
	 */
	public function getQueryObject($id)
	{
		return new Adm_QueryObject_Docente_Get($id);
	}
	
	public function findById($id)
	{
		$docente = $this->findModelById($id);
		
		$docenteData = $docente->toArray();
		$personaData = $docente->Persona->toArray();
		
		$departamentos = array_map(function ($relacion) {
			$codigo = $relacion->Departamento->codigo;
			$nombre = $relacion->Departamento->nombre;
			return array(
				'departamento_id' => $relacion->departamento_id,
				'departamento' => $nombre . ' (' . $codigo . ')',
			);
		}, $docente->DocenteDpto->getData());
		
		$docenteData['departamentos'] = $departamentos;
		return array_merge($docenteData, $personaData);
	}
}