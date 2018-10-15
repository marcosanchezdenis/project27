<?php
class Ins_CarreraController extends Base_Controller
{
    const CODIGO_OPERACION = 'ins-car';
    
    public function init()
    {
        parent::init();
    
        if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
            $this->goHomePage();
        }
    }
    
    public function indexAction()
    {
        $this->view->mainTitle = "Listado de Carreras";
        
        $carreras = $this->getRepository()->find();
        $this->view->carreras = $carreras;
    }
    
    public function getAction()
	{
		$this->view->mainTitle = "Datos de la Carrera";
		$id = $this->getParam("id");
		$carrera = $this->getRepository()->findById($id);
		$this->view->carrera = $carrera;
	}
	
	protected function getDepartamentos()
	{
	    $departamentoRepository = new Ins_Repository_Departamento();
	    $departamentos = $departamentoRepository->getDepartamentos();
	    return $departamentos;
	}
	
	public function crearAction()
	{
	    $this->view->mainTitle = "Agregar Carrera";
	    $this->view->departamentos = $this->getDepartamentos();
	    
	    if ($this->getRequest()->isPost()) {
	        $data = $this->getRequest()->getPost();
	        
	        $builder = new Ins_Builder_Carrera_DatosPrincipales($data, $this->getRepository());
	        $results = $builder->generateModels();
	        
	        if ($results["status"]) {
	            $id = $builder->save();
	            $this->_redirect('/ins/carrera/index');
	        }
	        
	        $this->view->carrera = $data;
	        $this->view->errors = $results["errors"];
	    }
	}
	
	public function editarAction()
	{
	    $this->view->mainTitle = "Edici&oacute;n de Carrera";
	    $this->view->departamentos = $this->getDepartamentos();
	
	    if ($this->getRequest()->isPost()) {
	        $data = $this->getRequest()->getPost();
	        $id = $data["carrera_id"];
	
	        $builder = new Ins_Builder_Carrera_DatosPrincipales($data, $this->getRepository());
	        $results = $builder->generateModels();
	
	        if ($results["status"]) {
	            $id = $builder->save();
	            $this->_redirect('/ins/carrera/index');
	        }
	
	        $this->view->carrera = $data;
	        $this->view->errors = $results["errors"];
	        $this->_redirect('/ins/carrera/editar?id=' . $id);
	    } else {
	        $id = $this->getParam("id");
	
	        $results = $this->getRepository()->findById($id);
	        $this->view->carrera = $results;
	    }
	}
	
	public function borrarAction()
	{
	    $id = $this->getParam("id");
	    $carrera = $this->getRepository()->findModelById($id);
	    
	    if (!count($carrera->Asignatura)) {
	        $carrera->delete();
	    }
	    
	    $this->_redirect('/ins/carrera/index');
	}
    
    public function getRepository()
    {
        return new Ins_Repository_Carrera();
    }
}