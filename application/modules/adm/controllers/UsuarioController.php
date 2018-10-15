<?php

/**
 * Funcionalidades para el manejo de Usuarios
 * @author Guido Arce
 */
class Adm_UsuarioController extends Base_Controller
{
    const CODIGO_OPERACION = 'adm-usu';
    
	public function init()
	{
	    // Agregar Contextos
	    $this->getContextSwitch()
	         ->addActionContext('roles', 'json')
	         ->addActionContext('usuarios', 'json');
	    
	    // Llamar al padre para inicializar contextos
		parent::init();
		
		// Controlar la Autorizacion
		if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
		    $this->goHomePage();
		}
	}
	
	/**
	 * Listado de Usuarios del Sistema
	 * @todo Agregar filtros de busqueda
	 */
	public function indexAction()
	{
		$this->view->mainTitle = "Usuarios del Sistema";
		$this->view->errors    = $this->_helper->flashMessenger->getMessages();
		
		$usuarios = $this->getRepository()->find();
		$this->view->usuarios = $usuarios;
	}
	
	/**
	 * Activar o desactivar cuenta de usuario
	 */
	public function activarAction()
	{
	    $id = $this->getParam("id");
	    $usuario = $this->getRepository()->findModelById($id);
	    $usuario->activo = $usuario->activo == 'S' ? 'N' : 'S';
	    $usuario->save();
	    $this->_redirect('/adm/usuario/index');
	}
	
	/**
	 * Visualizar datos del usuario
	 */
	public function getAction()
	{
		$this->view->mainTitle = "Datos del Usuario";
		
		$id = $this->getParam("id");
		$usuario = $this->getRepository()->findById($id);
		$this->view->usuario = $usuario;
	}
	
	public function rolesAction()
	{
		$this->_helper->json($this->getRoles());
	}
	
	public function usuariosAction()
	{
	    $this->_helper->json($this->getUsuarios());
	}
	
	public function getUsuarios()
	{
	    $usuarios = $this->getRepository()->find();
	    $usuarios = array_map(function ($usuario) {
	        return array(
	            "usuario_id" => $usuario['usuario_id'],
	            "usuario" => $usuario['usuario'],
	        );
	    }, $usuarios);
	    
	    return $usuarios;
	}
	
	public function getRoles()
	{
		$rolRepository = new Adm_Repository_Rol();
		$roles = $rolRepository->getAll();
	    return $roles;
	}

	public function changepassAction(){
		$perm = new Base_Helper_View_Permisos();
		$user = $perm->findLoggedUser();

		if ($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			if(strlen($data["contrasena"]) >= 5){
				if(strcmp($user->contrasena, sha1($data["passviejo"])) == 0){
					if(strcmp($data["contrasena"], $data["confirmacion"]) == 0){
						$user->contrasena = sha1($data["contrasena"]);
						$user->save();
					}else{
						$this->view->errors = array("confirmacion"=> "La contraseñas ingresadas no son iguales.");
					}
				}else{
					$this->view->errors = array("confirmacion"=> "Debes ingresar correctamente la contraseña actual.");
				}
			}else{
				$this->view->errors = array("contrasena"=> "La contraseña nueva debe tener al menos 5 caracteres.");
			}
		}

		$this->view->usuario = $user;
	}

	public function updatePasswordsAction(){
		$repo = new Adm_Repository_Usuario();
		$usersList = $repo->getUsersList();
		$fp = fopen("passwords.txt","wb");
		foreach($usersList as $userData){
			$user = $repo->findModelById($userData["usuario_id"]);
			$newpass = sha1("$".$user->usuario."#");
			$newpass = substr($newpass, 0, 5);
			fwrite($fp, "USER: ".$user->usuario."\t\tPASSWORD:".$newpass."\n");
			$newpass = sha1($newpass);
			$user->contrasena = $newpass;
			$user->save();
		}
		fclose($fp);
	}
	
	public function crearAction()
	{
		$this->view->mainTitle = "Creaci&oacute;n de Usuarios";
		$this->view->roles = $this->getRoles();
		
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			
			$builder = new Adm_Builder_Usuario_DatosPrincipales($data, $this->getRepository(), "post");
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/adm/usuario/get?id=' . $id);
			}
			
			$this->view->usuario = $data;
			$this->view->errors = $results["errors"];
		}
	}
	
	public function editarAction()
	{
		$this->view->mainTitle = "Edici&oacute;n de Usuario";
		$this->view->roles = $this->getRoles();
		
		if ($this->getRequest()->isPost()) {
		    $data = $this->getRequest()->getPost();
		    $id = $data["usuario_id"];
			
			$builder = new Adm_Builder_Usuario_DatosPrincipales($data, $this->getRepository(), "put");
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/adm/usuario/get?id=' . $id);
			}
			
			$this->view->usuario = $data;
			$this->view->errors = $results["errors"];
			$this->_redirect('/adm/usuario/editar?id=' . $id);
		} else {
		    $id = $this->getParam("id");
			$results = $this->getRepository()->findById($id);
			$this->view->usuario = $results;
		}
	}
	
	public function borrarAction()
	{
	    $id = $this->getParam("id");
	    $usuario = $this->getRepository()->findModelById($id);
	    
	    // Verificar si es docente
	    if (count($usuario->Persona->Docente) > 0) {
	        // Verificar si tiene alguna encuesta asociada
	        foreach ($this->Persona->Docente as $docente) {
	            if (count($docente->EncParticular) > 0) {
	                $this->_helper->flashMessenger->addMessage("El usuario no se puede eliminar<em><b>(Ya se utiliza en una encuesta)</b></em>");
	                $this->_redirect('/adm/usuario/index');
	            }
	        }
	        
	        $usuario->Persona->Docente->delete();
	    }
	    
	    // Eliminar los roles
	    $usuario->UsuarioPerm->delete();
	    
	    // Eliminar la persona
	    $persona = $usuario->Persona;
	    $usuario->delete();
	    $persona->delete();
	    
	    $this->_redirect('/adm/usuario/index');
	}
	
	/**
	 * @return Adm_Repository_Usuario
	 */
	public function getRepository()
	{
		return new Adm_Repository_Usuario();
	}
}