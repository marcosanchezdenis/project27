<?php
class Enc_Builder_Plantilla_PregAbierta extends Base_Builder
{
	protected $pregAbierta;
	
	public function __construct($data, $repository)
	{
		parent::__construct($data, $repository);
		$this->addPadreTipo();
		$this->convertirEsObligatoria();
	}
	
	public function convertirEsObligatoria()
	{
		if (isset($this->data["es_obligatoria"])) {
			$this->data["es_obligatoria"] = "S";
		} else {
			$this->data["es_obligatoria"] = "N";
		}
	}
	
	public function addPadreTipo()
	{
		if ($this->data["padre"] == "encuesta") {
			$padreTipo = "encuesta_id";
		} else {
			$padreTipo = "categoria_id";
		}
		
		$this->data[$padreTipo] = $this->data["padre-id"];
	}
	
	public function getModel()
	{
		if (isset($this->data["pregunta_id"]) && $this->data["pregunta_id"] != "") {
			return $this->repository->findPreguntaById($this->data["pregunta_id"]);
		}
		unset($this->data["pregunta_id"]);
		return new Model_Pregunta();
	}
	
	public function generateModels()
	{
		$this->pregAbierta = $this->getModel();
		$this->pregAbierta->fromArray($this->getData());
		$this->pregAbierta->addOrden($this->data["padre"]);
		
		// Validar los datos
		
		return array("status" => true);
	}
	
	public function save()
	{
		$this->pregAbierta->save();
		
		$id = $this->pregAbierta->get('pregunta_id');
		return $id;
	}
}