<?php
class Enc_Builder_Escala_Escala extends Base_Builder
{
/**
	 * Modelo de la Plantilla
	 * @var Model_Escala
	 */
	protected $escala;
	protected $method;
	
	protected $deletedData = array();
	protected $usedData = array();
	
	public function __construct($data, $repository, $method)
	{
		parent::__construct($data, $repository);
		$this->method = $method;
		$this->cleanData();
	}
	
	public function cleanData()
	{
		if ($this->data["escala_id"] == "" || $this->data["escala_id"] == null) {
			unset($this->data["escala_id"]);
		}
	}
	
	public function getModel()
	{
		if (isset($this->data["escala_id"]) && $this->data["escala_id"] != "") {
			return $this->repository->findModelById($this->data["escala_id"]);
		}
		return new Model_Escala();
	}
	
	public function updateValores()
	{
	    if ($this->method === "post") {
	        if (isset($this->data['escala_valor'])) {
	            for ($i=0; $i<count($this->data['escala_valor']); $i++) {
	                $valor = new Model_EscalaValor();
	                $valor->valor = intval($this->data['escala_valor'][$i]['valor']);
	                $valor->descripcion = $this->data['escala_valor'][$i]['descrip'];
	                $this->escala->EscalaValor[] = $valor;
	            }
	        }
	    } else {
	        $valoresOld = array_map(function ($valor) {
	            return array(
	                'escala_valor_id' => $valor['escala_valor_id'],
	                'valor'           => $valor['valor'],
	                'descripcion'     => $valor['descripcion'],
	            );
	        }, $this->escala->EscalaValor->getData());
	    
	        if (isset($this->data['escala_valor'])) {
	    
	            for ($i=0; $i<count($valoresOld); $i++) {
	                $found = false;
	                for ($j=0; $j<count($this->data['escala_valor']); $j++) {
	                    if ($valoresOld[$i]['escala_valor_id'] == $this->data['escala_valor'][$j]['escala_valor_id']) {
	                        $found = true;
	                        break;
	                    }
	                }
	                
	                if (!$found) {
	                    $this->deletedData[] = $valoresOld[$i]['escala_valor_id'];
	                }
	            }
	    
	            for ($i=0; $i<count($this->data['escala_valor']); $i++) {
	                if ($this->data['escala_valor'][$i]['escala_valor_id'] == "new") {
	                    $valor = new Model_EscalaValor();
	                    $valor->valor = intval($this->data['escala_valor'][$i]['valor']);
	                    $valor->descripcion = $this->data['escala_valor'][$i]['descrip'];
	                    $this->escala->EscalaValor[] = $valor;
	                }
	            }
	    
	        } else {
	            $this->deletedData = array_map(function ($valor) {
	                return $valor['escala_valor_id'];
	            }, $valoresOld);
	        }
	    }
	}
	
	public function generateModels()
	{
		// Obtener, actualizar y validar
		$this->escala = $this->getModel();
		$this->escala->fromArray($this->getData());
		
		$this->updateValores();
		
		$validator = new Enc_Validator_Escala_Escala();
		if (!$validator->isValid($this->escala)) {
			return array(
				"status" => false,
				"errors" => $validator->getMessages(),
			);
		}
		
		return array("status" => true);
	}
	
	public function save()
	{
		$this->escala->save();
		$id = $this->escala->get('escala_id');
		
		if (count($this->deletedData) > 0) {
		    $this->deleteValores();
		}
		
		return $id;
	}
	
	public function deleteValores()
	{
	    $query = Doctrine_Query::create()
	        ->delete('Model_EscalaValor')
	        ->whereIn('escala_valor_id', $this->deletedData);
	    
	    $numDeleted = $query->execute();
	}
}