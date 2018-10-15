<?php
class Ins_Validator_Departamento_DatosPrincipales extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($departamento)
	{
	    $this->validateNombre($departamento);
		$this->validateCodigo($departamento);
	}
	
	public function isValid($departamento)
	{
		$this->validateFields($departamento);
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
	
	public function validateNombre($departamento)
	{
	    $departamentoValidator = new Zend_Validate();
	    $emptyValidator = new Zend_Validate_NotEmpty();
	    $emptyValidator->setMessage("El campo <b>'NOMBRE'</b> es obligatorio");
	    $lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
	    $lengthValidator->setMessage("Nombre muy largo <em>(M&aacute;x: %max% caracteres)</em>");
	     
	    $departamentoValidator->addValidator($emptyValidator)
	                          ->addValidator($lengthValidator);
	     
	    $this->validators[] = $departamentoValidator;
	    $this->validatedFields[] = $departamento->nombre;
	}
	
	public function validateCodigo($departamento)
	{
		$codigoValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'CODIGO'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 10));
		$lengthValidator->setMessage("Codigo muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$codigoValidator->addValidator($emptyValidator)
						->addValidator($lengthValidator);
		
		$this->validators[] = $codigoValidator;
		$this->validatedFields[] = $departamento->codigo;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}