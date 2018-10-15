<?php
class Base_Builder
{
    protected $data;
    protected $repository;
    
    public function __construct($data, $repository)
    {
        $this->data = $data;
        $this->repository = $repository;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function build()
    {
        return $this->generateModels();
    }
    
    /**
     * Genera los modelos correspondientes para almacenar en la Base de Datos
     */
    public function generateModels()
    {
        // Implementar en cada clase
    }
    
    /**
     * Guarda los modelos generados en la Base de Datos
     */
    public function save()
    {
        // Implementar en cada clase
    }
}