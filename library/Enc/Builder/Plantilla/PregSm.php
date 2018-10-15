<?php
class Enc_Builder_Plantilla_PregSm extends Base_Builder
{
	protected $pregSm;
	
	protected $deletedData = array();
	protected $updatedData = array();
	
	public function __construct($data, $repository)
	{
		parent::__construct($data, $repository);
		$this->addPadreTipo();
		$this->convertirEsObligatoria();
		$this->convertirEsSelMultiple();
		//throw new Exception(print_r($this->data, true));
	}
	
	public function convertirEsObligatoria()
	{
		if (isset($this->data["es_obligatoria"])) {
			$this->data["es_obligatoria"] = "S";
		} else {
			$this->data["es_obligatoria"] = "N";
		}
	}
	
	public function convertirEsSelMultiple()
	{
	    if (isset($this->data["opcion_multiple"])) {
	        $this->data["opcion_multiple"] = "S";
	    } else {
	        $this->data["opcion_multiple"] = "N";
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
		$this->pregSm = $this->getModel();
		$this->pregSm->fromArray($this->getData());
		$this->pregSm->addOrden($this->data["padre"]);
		
		$this->updateOpciones();
		// Validar los datos
		
		return array("status" => true);
	}
	
	public function getOpcionesRecibidas()
	{
	    $opciones = array();
	    for ($i=1; isset($this->data["opcion-" . $i]); $i++) {
	        $opciones[] = array(
	            "valor"  => $i,
	            "nombre" => $this->data["opcion-" . $i],
            );
	    }
	    return $opciones;
	}
	
	public function updateOpciones()
	{
	    $opcionesOld = array_map(function ($opcion) {
	        return array(
	            'pregunta_sm_id' => $opcion['pregunta_sm_id'],
	            'nombre'         => $opcion['nombre'],
            );
        }, $this->pregSm->PreguntaSm->getData());
        
        if (isset($this->data['opcion_valor'])) {
            for ($i=0; $i<count($opcionesOld); $i++) {
                $found = false;
                for ($j=0; $j<count($this->data['opcion_valor']); $j++) {
                    if ($opcionesOld[$i]['pregunta_sm_id'] == $this->data['opcion_valor'][$j]['pregunta_sm_id']) {
                        $found = true;
                        $this->updatedData[] = array(
                        	'pregunta_sm_id' => $this->data['opcion_valor'][$j]['pregunta_sm_id'],
                        	'nombre' => $this->data['opcion_valor'][$j]['nombre'],
                        );
                        break;
                    }
                }
                
                if (!$found) {
                    $this->deletedData[] = $opcionesOld[$i]['pregunta_sm_id'];
                }
            }
            
            for ($i=0; $i<count($this->data['opcion_valor']); $i++) {
                if ($this->data['opcion_valor'][$i]['pregunta_sm_id'] == "new") {
                    $opcion = new Model_PreguntaSm();
                    $opcion->nombre = $this->data['opcion_valor'][$i]['nombre'];
                    $this->pregSm->PreguntaSm[] = $opcion;
                }
            }
        } else {
            $this->deletedData = array_map(function ($valor) {
                return $valor['pregunta_sm_id'];
            }, $opcionesOld);
        }
        
	    /*$opcionesGuardadas = $this->pregSm->getOpciones();
	    $opcionesRecibidas = $this->getOpcionesRecibidas();
	    $opcionesAborrar   = array();
	    
	    for ($i=0; $i < count($opcionesGuardadas); $i++) {
	        $found = false;
	        for ($j=0; $j < count($opcionesRecibidas); $j++) {
	            if ($opcionesGuardadas[$i]["nombre"] == $opcionesRecibidas[$j]["nombre"]) {
	                $found = true;
	                unset($opcionesRecibidas[$j]);
	                $opcionesRecibidas = array_values($opcionesRecibidas);
	                break;
	            }
	        }
	        
	        if (!$found) {
	            $opcionesAborrar[] = $opcionesGuardadas[$i]["pregunta_sm_id"];
	        }
	    }
	    
	    foreach ($this->pregSm->PreguntaSm as &$opcionModel) {
	        if (in_array($opcionModel->pregunta_sm_id, $opcionesAborrar)) {
	            $opcionModel->delete();
	        }
	    }
	    
	    foreach ($opcionesRecibidas as $opcionData) {
	        $opcion = new Model_PreguntaSm();
	        $opcion->valor = $opcionData["valor"];
	        $opcion->nombre = $opcionData["nombre"];
	        
	        $this->pregSm->PreguntaSm[] = $opcion;
	    }*/
	}
	
	public function save()
	{
		if (count($this->updatedData) > 0) {
			$this->updateOpcionesSm();
		}
		
		$this->pregSm->save();
		
		if (count($this->deletedData) > 0) {
		    $this->deleteOpciones();
		}
		
		$id = $this->pregSm->get('pregunta_id');
		return $id;
	}
	
	public function updateOpcionesSm()
	{
		foreach ($this->pregSm->PreguntaSm as &$opcion) {
			for ($i=0; $i<count($this->updatedData); $i++) {
				if ($opcion->pregunta_sm_id == $this->updatedData[$i]["pregunta_sm_id"]) {
					$opcion->nombre = $this->updatedData[$i]["nombre"];
					break;
				}
			}
		}
	}
	
	public function deleteOpciones()
	{
	    $query = Doctrine_Query::create()
	        ->delete('Model_PreguntaSm')
	        ->whereIn('pregunta_sm_id', $this->deletedData);
	    
	    $numDeleted = $query->execute();
	}
}