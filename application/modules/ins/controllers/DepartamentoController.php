<?php
class Ins_DepartamentoController extends Base_Controller
{
    const CODIGO_OPERACION = 'ins-dep';
    
    public function init()
    {
        parent::init();
        
        if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
            $this->goHomePage();
        }
    }
    
    public function indexAction()
    {
        $this->view->mainTitle = "Listado de Departamentos";
        
        $departamentos = $this->getRepository()->find();
        $this->view->departamentos = $departamentos;
    }
    
    public function getAction()
    {
        $this->view->mainTitle = "Datos del Departamento";
        $id = $this->getParam("id");
        $departamento = $this->getRepository()->findById($id);
        $this->view->departamento = $departamento;
    }
    
    protected function getFacultades()
    {
        $facultadRepository = new Ins_Repository_Facultad();
        $facultades = $facultadRepository->getFacultades();
        return $facultades;
    }
    
	public function crearAction()
	{
	    $this->view->mainTitle = "Agregar Departamento";
	    $this->view->facultades = $this->getFacultades();
	    
	    if ($this->getRequest()->isPost()) {
	        $data = $this->getRequest()->getPost();
	        
	        $builder = new Ins_Builder_Departamento_DatosPrincipales($data, $this->getRepository());
	        $results = $builder->generateModels();
	        
	        if ($results["status"]) {
	            $id = $builder->save();
	            $this->_redirect('/ins/departamento/index');
	        }
	        
	        $this->view->departamento = $data;
	        $this->view->errors = $results["errors"];
	    }
	}
	
	public function editarAction()
	{
	    $this->view->mainTitle = "Edici&oacute;n de Departamento";
	    $this->view->facultades = $this->getFacultades();
	     
	    if ($this->getRequest()->isPost()) {
	        $data = $this->getRequest()->getPost();
	        $id = $data["departamento_id"];
	
	        $builder = new Ins_Builder_Departamento_DatosPrincipales($data, $this->getRepository());
	        $results = $builder->generateModels();
	
	        if ($results["status"]) {
	            $id = $builder->save();
	            $this->_redirect('/ins/departamento/index');
	        }
	
	        $this->view->departamento = $data;
	        $this->view->errors = $results["errors"];
	        $this->_redirect('/ins/departamento/editar?id=' . $id);
	    } else {
	        $id = $this->getParam("id");
	         
	        $results = $this->getRepository()->findById($id);
	        $this->view->departamento = $results;
	    }
	}
    
    public function borrarAction()
    {
        $id = $this->getParam("id");
        $departamento = $this->getRepository()->findModelById($id);
        
        if (!count($departamento->Carrera)) {
            $departamento->delete();
        }
        
        $this->_redirect('/ins/departamento/index');
    }
    
    public function getRepository()
    {
        return new Ins_Repository_Departamento();
    }
}