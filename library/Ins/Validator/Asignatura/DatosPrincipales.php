<?php
class Ins_Validator_Asignatura_DatosPrincipales extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($asignatura)
	{
		$this->validateCodigo($asignatura);
		$this->validateActivo($asignatura);
	}
	
	public function isValid($asignatura)
	{
		$this->validateFields($asignatura);
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
	
	public function validateCodigo($asignatura)
	{
		$codigoValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'CODIGO'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 10));
		$lengthValidator->setMessage("Codigo muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$codigoValidator->addValidator($emptyValidator)
						->addValidator($lengthValidator);
		
		$this->validators[] = $codigoValidator;
		$this->validatedFields[] = $asignatura->codigo;
	}
	
	public function validateActivo($asignatura)
	{
	    $activoValidator = new Zend_Validate();
	    $emptyValidator = new Zend_Validate_NotEmpty();
	    $emptyValidator->setMessage("El campo <b>'ACTIVO'</b> es obligatorio");
	    $valueValidator = new Zend_Validate_InArray(array("S", "N"));
	    $valueValidator->setMessage("Valor inv&aacute;lido <em>(Solo se permiten valores 'S' y 'N')</em>");
	    
	    $activoValidator->addValidator($emptyValidator)
	                    ->addValidator($valueValidator);
	    
	    $this->validators[] = $activoValidator;
	    $this->validatedFields[] = $asignatura->activo;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}