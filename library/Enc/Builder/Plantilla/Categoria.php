<?php
class Enc_Builder_Plantilla_Categoria extends Base_Builder
{
	protected $categoria;
	
	public function __construct($data, $repository)
	{
		parent::__construct($data, $repository);
		$this->addPadreTipo();
	}
	
	public function addPadreTipo()
	{
		if ($this->data["padre"] == "encuesta") {
			$padreTipo = "encuesta_id";
		} else {
			$padreTipo = "categoria_padre";
		}
		
		$this->data[$padreTipo] = $this->data["padre-id"];
	}
	
	public function getModel()
	{
		if (isset($this->data["categoria_id"]) && $this->data["categoria_id"] != "") {
			return $this->repository->findCategoriaById($this->data["categoria_id"]);
		}
		unset($this->data["categoria_id"]);
		return new Model_Categoria();
	}
	
	public function generateModels()
	{
		$this->categoria = $this->getModel();
		$this->categoria->fromArray($this->getData());
		$this->categoria->addOrden($this->data["padre"]);
		
		// Validar los datos
		
		return array("status" => true);
	}
	
	public function save()
	{
		$this->categoria->save();
		
		$id = $this->categoria->get('categoria_id');
		return $id;
	}
}