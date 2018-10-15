<?php
class Adm_Auth_Form extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->setName('login-form');
        
        $this->setAction('index')
             ->setMethod('post');
        
        $usuario = $this->createElement('text', 'usuario');
        $usuario->setLabel('USUARIO:')
                ->setRequired(true);
        
        $contrasena = $this->createElement('password', 'contrasena');
        $contrasena->setLabel('CONTRASENA:')
                   ->setRequired(true);
        
        $login = $this->createElement('submit', 'login');
        $login->setLabel('Ingresar');
        
        $this->addElement($usuario)->addElement($contrasena)->addElement($login);
        
        $this->addDisplayGroup(array('usuario', 'contrasena', 'login'), 'login-form', array('legend' => 'INICIAR SESION'));
    }
}