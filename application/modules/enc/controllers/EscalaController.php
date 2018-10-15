<?php
class Enc_EscalaController extends Base_Controller
{
    const CODIGO_OPERACION = 'enc-esc';
    
    public function init()
    {
        parent::init();
    
        if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
            $this->goHomePage();
        }
    }
    
    public function indexAction()
    {
    	$this->view->mainTitle = "Listado de Escalas";
    	
        $results = $this->getRepository()->find();
        $this->view->results = $results;
    }
    
    public function getAction()
    {
    	$this->view->mainTitle = "Detalles de la Escala";
    	
        $id = $this->getParam("id");
        $results = $this->getRepository()->findById($id);
        $this->view->results = $results;
    }
    
    public function crearAction()
    {
	    $this->view->mainTitle = "Creaci&oacute;n de Escalas";
		$this->view->escala = array("escala_id" => "", "nombre" => "", "descripcion" => "");
		
		if ($this->getRequest()->isPost()) {
		    $data = $this->getRequest()->getPost();
			
			$builder = new Enc_Builder_Escala_Escala($data, $this->getRepository(), "post");
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/enc/escala/get?id=' . $id);
			}
			
			$this->view->escala = $data;
			$this->view->errors = $results["errors"];
		}
    }
    
    public function editarAction()
    {
        $this->view->mainTitle = "Edici&oacute;n de Escalas";
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $id = $data["escala_id"];
            	
            $builder = new Enc_Builder_Escala_Escala($data, $this->getRepository(), "put");
            $results = $builder->generateModels();
            	
            if ($results["status"]) {
                $id = $builder->save();
                $this->_redirect('/enc/escala/get?id=' . $id);
            }
            	
            $this->view->escala = $data;
            $this->view->errors = $results["errors"];
            $this->_redirect('/enc/escala/editar?id=' . $id);
        } else {
            $id = $this->getParam("id");
            $results = $this->getRepository()->findById($id);
            $this->view->escala = $results;
        }
    }
    
	public function borrarAction()
	{
		$id = $this->getParam("id");
		$escala = $this->getRepository()->findModelById($id);
		// Controlar que no tenga ninguna Encuesta asociada
		// Si no tiene, se borra
		$escala->borrar();
		$this->_redirect('/enc/escala/index');
	}
    
    public function getRepository()
    {
        return new Enc_Repository_Escala();
    }
}