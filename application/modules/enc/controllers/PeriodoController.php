<?php
class Enc_PeriodoController extends Base_Controller
{
    const CODIGO_OPERACION = 'enc-per';
    
    public function init()
    {
        parent::init();
    
        if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
            $this->goHomePage();
        }
    }
    
    public function indexAction()
    {
        $this->view->mainTitle = "Periodos Lectivos";
        $this->view->errors    = $this->_helper->flashMessenger->getMessages();
        
        $periodos = $this->getRepository()->find();
        $this->view->periodos = $periodos;
    }
    
    protected function getPeriodos()
    {
        $periodoRepository = new Enc_Repository_Periodo();
        $periodos = $periodoRepository->getPeriodos();
        return $periodos;
    }

	public function visibleProfAction()
	{
		$id = $this->getParam("id");
		$periodo = $this->getRepository()->findModelById($id);
		$periodo->visible_profesores = ($periodo->visible_profesores == "S") ? ("N") : ("S");
		$periodo->save();
		$this->_redirect('/enc/periodo/index');
	}
    
    public function crearAction()
    {
        $this->view->mainTitle = "Nuevo Periodo Lectivo";
        $this->view->periodos = $this->getPeriodos();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            	
            $builder = new Enc_Builder_Periodo_DatosPrincipales($data, $this->getRepository());
            $results = $builder->generateModels();
            	
            if ($results["status"]) {
                $id = $builder->save();
                $this->_redirect('/enc/periodo/index');
            }
            	
            $this->view->periodo = $data;
            $this->view->errors = $results["errors"];
        }
    }
    
    public function activarAction()
    {
        $id = $this->getParam("id");
        $periodo = $this->getRepository()->findModelById($id);
        $periodo->activo = ($periodo->activo == "S") ? ("N") : ("S");
        $periodo->save();
        $this->_redirect('/enc/periodo/index');
    }
    
    public function borrarAction()
    {
        $id = $this->getParam("id");
        $periodo = $this->getRepository()->findModelById($id);
        
        if (count($periodo->EncParticular)) {
            $this->_helper->flashMessenger->addMessage("El periodo no se puede borrar <em><b>(Ya se utiliza en una encuesta)</b></em>");
            $this->_redirect('/enc/periodo/index');
        } else {
            $periodo->delete();
            $this->_redirect('/enc/periodo/index');
        }
    }
    
    public function getRepository()
    {
        return new Enc_Repository_Periodo();
    }
}