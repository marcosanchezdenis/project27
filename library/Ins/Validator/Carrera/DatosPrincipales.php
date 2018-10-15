<?php
class Ins_Validator_Carrera_DatosPrincipales extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($carrera)
	{
	    $this->validateNombre($carrera);
		$this->validateCodigo($carrera);
	}
	
	public function isValid($carrera)
	{
		$this->validateFields($carrera);
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
	
	public function validateNombre($carrera)
	{
	    $carreraValidator = new Zend_Validate();
	    $emptyValidator = new Zend_Validate_NotEmpty();
	    $emptyValidator->setMessage("El campo <b>'NOMBRE'</b> es obligatorio");
	    $lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
	    $lengthValidator->setMessage("Nombre muy largo <em>(M&aacute;x: %max% caracteres)</em>");
	     
	    $carreraValidator->addValidator($emptyValidator)
	                          ->addValidator($lengthValidator);
	     
	    $this->validators[] = $carreraValidator;
	    $this->validatedFields[] = $carrera->nombre;
	}
	
	public function validateCodigo($carrera)
	{
		$codigoValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'CODIGO'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 10));
		$lengthValidator->setMessage("Codigo muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$codigoValidator->addValidator($emptyValidator)
						->addValidator($lengthValidator);
		
		$this->validators[] = $codigoValidator;
		$this->validatedFields[] = $carrera->codigo;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}