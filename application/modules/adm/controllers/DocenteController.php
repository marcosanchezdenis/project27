<?php

/**
 * Funcionalidades para el manejo de Docentes
 * @author Guido Arce
 */
class Adm_DocenteController extends Base_Controller
{
    const CODIGO_OPERACION = 'adm-doc';
    
    /**
     * @see Base_Controller::init()
     */
    public function init()
    {
        // Agregar Contextos
	    $this->getContextSwitch()
	         ->addActionContext('departamentos', 'json');
	    
	    // Llamar al padre para inicializar contextos
		parent::init();
		
		// Controlar la Autorizacion
		if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
		    $this->goHomePage();
		}
	}
	
	/**
	 * Listado de Docentes del Sistema
	 * @todo Agregar filtros de busqueda
	 */
	public function indexAction()
	{
		$this->view->mainTitle = "Plantel Docente";
		
		$docentes = $this->getRepository()->find();
		$this->view->docentes = $docentes;
	}
	
	public function getAction()
	{
		$this->view->mainTitle = "Ficha del Docente";
		$id = $this->getParam("id");
		$docente = $this->getRepository()->findById($id);
		$this->view->docente = $docente;
	}
	
	public function departamentosAction()
	{
		$this->_helper->json($this->getDepartamentos());
	}
	
	public function getDepartamentos()
	{
		$dptoRepository = new Ins_Repository_Departamento();
		$dptos = $dptoRepository->getAll();
	    return $dptos;
	}
	
	public function editarAction()
	{
		$this->view->mainTitle = "Edici&oacute;n de Docente";
		$this->view->departamentos = $this->getDepartamentos();
		
		if ($this->getRequest()->isPost()) {
		    $data = $this->getRequest()->getPost();
		    $id = $data["docente_id"];
			
			$builder = new Adm_Builder_Docente_DatosPrincipales($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/adm/docente/get?id=' . $id);
			}
			
			$this->view->docente = $data;
			$this->view->errors = $results["errors"];
			$this->_redirect('/adm/docente/editar?id=' . $id);
		} else {
		    $id = $this->getParam("id");
			$results = $this->getRepository()->findById($id);
			$this->view->docente = $results;
		}
	}
	
	public function activarAction()
	{
	    $id = $this->getParam("id");
	    $docente = $this->getRepository()->findModelById($id);
	    $docente->activo = $docente->activo == 'S' ? 'N' : 'S';
	    $docente->save();
	    $this->_redirect('/adm/docente/index');
	}
	
	public function getRepository()
	{
		return new Adm_Repository_Docente();
	}
}