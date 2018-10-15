<?php
class Adm_Validator_Usuario_DatosPrincipales extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($usuario)
	{
		$this->validateUsuario($usuario);
		$this->validateContrasena($usuario);
		$this->validateActivo($usuario);
	}
	
	public function isValid($usuario)
	{
		$this->validateFields($usuario);
		$isValidModel = true;
		
		$numOfValidators = count($this->validators);
		for ($i=0; $i<$numOfValidators; $i++) {
			$isValidField = $this->validators[$i]->isValid($this->validatedFields[$i]);
			if (!$isValidField) {
				$isValidModel = false;
				$this->validationErrors[] = $this->validators[$i]->getMessages();
			}
		}
		
		return $isValidModel;
	}
	
	public function validateUsuario($usuario)
	{
		$usuarioValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'USUARIO'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
		$lengthValidator->setMessage("Nombre de Usuario muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$usuarioValidator->addValidator($emptyValidator)
						 ->addValidator($lengthValidator);
		
		$this->validators[] = $usuarioValidator;
		$this->validatedFields[] = $usuario->usuario;
	}
	
	public function validateContrasena($usuario)
	{
	    $contrasenaValidator = new Zend_Validate();
	    $emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'CONTRASE&Ntilde;A'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 40));
		$lengthValidator->setMessage("Contrase&ntilde;a muy larga <em>(M&aacute;x: %max% caracteres)</em>");
		
		$contrasenaValidator->addValidator($emptyValidator)
		                    ->addValidator($lengthValidator);
		
		$this->validators[] = $contrasenaValidator;
		$this->validatedFields[] = $usuario->contrasena;
	}
	
	public function validateActivo($usuario)
	{
	    $activoValidator = new Zend_Validate();
	    $emptyValidator = new Zend_Validate_NotEmpty();
	    $emptyValidator->setMessage("El campo <b>'ACTIVO'</b> es obligatorio");
	    $valueValidator = new Zend_Validate_InArray(array("S", "N"));
	    $valueValidator->setMessage("Valor inv&aacute;lido <em>(Solo se permiten valores 'S' y 'N')</em>");
	     
	    $activoValidator->addValidator($emptyValidator)
	                    ->addValidator($valueValidator);
	     
	    $this->validators[] = $activoValidator;
	    $this->validatedFields[] = $usuario->activo;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}