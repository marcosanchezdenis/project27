<?php
class Enc_Builder_Plantilla_DatosPrincipales extends Base_Builder
{
	/**
	 * Modelo de la Plantilla
	 * @var Model_Encuesta
	 */
	protected $plantilla;
	
	public function __construct($data, $repository)
	{
		parent::__construct($data, $repository);
		$this->cleanData();
		$this->setNoActivoPorDefecto();
		$this->addCurrentDate();
	}
	
	public function cleanData()
	{
		if ($this->data["encuesta_id"] == "" || $this->data["encuesta_id"] == null) {
			unset($this->data["encuesta_id"]);
		}
	}
	
	public function setNoActivoPorDefecto()
	{
		if (isset($this->data["activo"]) && $this->data["activo"] == "") {
			$this->data["activo"] = "N";
		}
	}
	
	/**
	 * Agrega la fecha actual de ultima modificacion
	 */
	public function addCurrentDate()
	{
		$this->data["fecha"] = date("Y-m-d");
	}
	
	public function getModel()
	{
		if (isset($this->data["encuesta_id"]) && $this->data["encuesta_id"] != "") {
			return $this->repository->findModelById($this->data["encuesta_id"]);
		}
		return new Model_Encuesta();
	}
	
	public function generateModels()
	{
		// Obtener, actualizar y validar
		$this->plantilla = $this->getModel();
		$this->plantilla->fromArray($this->getData());
		
		$validator = new Enc_Validator_Plantilla_DatosPrincipales();
		if (!$validator->isValid($this->plantilla)) {
			return array(
				"status" => false,
				"errors" => $validator->getMessages(),
			);
		}
		
		return array("status" => true);
	}
	
	public function save()
	{
		// Guardar en la BD
		$this->plantilla->save();
		
		// Retornar el ID de la plantilla modificada
		$id = $this->plantilla->get('encuesta_id');
		return $id;
	}
}