<?php

/**
 * Adaptador de Autenticacion al Sistema
 * @author Guido Arce
 */
class Adm_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
    // Mensajes de error
    const NOT_FOUND_MESSAGE    = "USUARIO NO REGISTRADO";
    const BAD_PASSWORD_MESSAGE = "CONTRASEÑA INCORRECTA";
    const NOT_ACTIVE_MESSAGE   = "USUARIO INACTIVO";
    
    /**
     * Usuario para autenticar
     * @var Model_Usuario
     */
    protected $user;
    
    /**
     * Nombre de Usuario
     * @var string
     */
    protected $username;
    
    /**
     * Contraseña de Usuario
     * @var string
     */
    protected $password;
    
    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    
    /**
     * Autenticador de Usuarios
     * @see Zend_Auth_Adapter_Interface::authenticate()
     */
    public function authenticate()
    {
		try {
            $user = Model_Usuario::authenticate($this->username, $this->password);
            $this->user = $user;
        } catch (Exception $e) {
            if ($e->getMessage() == Model_Usuario::USER_NOT_FOUND) {
                return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::NOT_FOUND_MESSAGE);
            }
            
            if ($e->getMessage() == Model_Usuario::WRONG_PASSWORD) {
                return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_PASSWORD_MESSAGE);
            }
            
            if ($e->getMessage() == Model_Usuario::USER_NOT_ACTIVE) {
                return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::NOT_ACTIVE_MESSAGE);
            }
        }
        
        return $this->result(Zend_Auth_Result::SUCCESS);
    }
    
    /**
     * Resultado de la autenticacion
     * @param string $code
     * @param array|string $messages
     * @return Zend_Auth_Result
     */
    public function result($code, $messages = array())
    {
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        
        return new Zend_Auth_Result($code, $this->user, $messages);
    }
}