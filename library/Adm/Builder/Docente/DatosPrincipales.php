<?php
class Adm_Builder_Docente_DatosPrincipales extends Base_Builder
{
	/**
	 * @var Model_Docente
	 */
	protected $docente;
	
	protected $deletedData = array();
	protected $usedData = array();
	
	/**
	 * @var Model_Persona
	 */
	protected $persona;
	
	public function __construct($data, $repository)
	{
		parent::__construct($data, $repository);
		$this->setActivoPorDefault();
		$this->addCodigoDocente();
	}
	
	public function setActivoPorDefault()
	{
	    if (isset($this->data["activo"]) && $this->data["activo"] == "") {
	        $this->data["activo"] = "S";
	    }
	}
	
	public function addCodigoDocente()
	{
	    if (isset($this->data["codigo"]) && $this->data["codigo"] == "") {
	        $this->data["codigo"] = $this->getCodigoPorDefault();
	    }
	}
	
	public function getCodigoPorDefault()
	{
	    $query = new Adm_QueryObject_Docente_NextCodigo();
	    $docente = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    if ($docente[0]["codigo"] == "" || $docente[0]["codigo"] == null) {
	        $docente[0]["codigo"] = 0;
	    }
	    
	    $codigoDocente = $docente[0]["codigo"] + 1;
	    return "$codigoDocente";
	}
	
	public function getModel()
	{
		if (isset($this->data["docente_id"]) && $this->data["docente_id"] != "") {
			return $this->repository->findModelById($this->data["docente_id"]);
		}
		return new Model_Docente();
	}
	
	public function getData($type)
	{
		$data = parent::getData();
		$newData = array();
		
		if ($type === "docente") {
			$keys = array("codigo", "activo");
		} else {
			$keys = array("nombre", "apellido", "email");
		}
		
		for ($i=0; $i<count($keys); $i++) {
			$newData[$keys[$i]] = $data[$keys[$i]];
		}
		
		return $newData;
	}
	
	public function updateDepartamentos()
	{
		$dptosOld = array_map(function ($relacion) {
			return array(
				'docente_dpto_id' => $relacion['docente_dpto_id'],
				'departamento_id' => $relacion['departamento_id'],
			);
		}, $this->docente->DocenteDpto->getData());
		
		//throw new Exception(print_r($this->data['departamentos'], true));
		//throw new Exception(print_r($dptosOld, true));
		
		if (isset($this->data['departamentos'])) {
			
			for ($i=0; $i<count($dptosOld); $i++) {
				if (!in_array($dptosOld[$i]['departamento_id'], $this->data['departamentos'])) {
					$this->deletedData[] = $dptosOld[$i]['docente_dpto_id'];
				} else {
					$this->usedData[] = $dptosOld[$i]['departamento_id'];
				}
			}
			
			//throw new Exception(print_r($this->deletedData, true));
			
			for ($i=0; $i<count($this->data['departamentos']); $i++) {
				if (!in_array($this->data['departamentos'][$i], $this->usedData)) {
					$this->docente->DocenteDpto[]->departamento_id = $this->data['departamentos'][$i];
				}
			}
		
		} else {
			$this->deletedData = array_map(function ($dpto) {
				return $dpto['docente_dpto_id'];
			}, $dptosOld);
		}
	}
	
	public function generateModels()
	{
		$this->docente = $this->getModel();
		$this->docente->fromArray($this->getData("docente"));
		$this->docente->Persona->fromArray($this->getData("persona"));
		
		$this->updateDepartamentos();
		
		$docenteValidator = new Adm_Validator_Docente_DatosPrincipales();
		$personaValidator = new Adm_Validator_Persona();
		
		if (!$docenteValidator->isValid($this->docente) || !$personaValidator->isValid($this->docente->Persona)) {
			$docenteErrors = $docenteValidator->getMessages();
			$personaErrors = $personaValidator->getMessages();
			
			return array(
				"status" => false,
				"errors" => array_merge($docenteErrors, $personaErrors),
			);
		}
		
		return array("status" => true);
	}
	
	public function save()
	{
		$this->docente->save();
		$id = $this->docente->get("docente_id");
		
		if (count($this->deletedData) > 0) {
            $this->deleteDepartamentos();
        }
		
		return $id;
	}
	
	public function deleteDepartamentos()
    {
        $query = Doctrine_Query::create()
             ->delete('Model_DocenteDpto')
             ->whereIn('docente_dpto_id', $this->deletedData);
        $numDeleted = $query->execute();
    }
}