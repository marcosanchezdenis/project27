<?php
class Enc_Builder_Periodo_DatosPrincipales extends Base_Builder
{
    protected $periodo;
    
    public function __construct($data, $repository)
    {
        parent::__construct($data, $repository);
        $this->cleanData();
        $this->setNoActivoPorDefecto();
    }
    
    public function cleanData()
    {
        if ($this->data["periodo_lectivo_id"] == "" || $this->data["periodo_lectivo_id"] == null) {
            unset($this->data["periodo_lectivo_id"]);
        }
    }
    
    public function setNoActivoPorDefecto()
    {
        if (isset($this->data["activo"]) && $this->data["activo"] == "") {
            $this->data["activo"] = "N";
        }
    }
    
    public function getModel()
    {
        if (isset($this->data["periodo_lectivo_id"]) && $this->data["periodo_lectivo_id"] != "") {
            return $this->repository->findModelById($this->data["periodo_lectivo_id"]);
        }
        return new Model_PeriodoLectivo();
    }
    
    public function generateModels()
    {
        // Obtener, actualizar y validar
        $this->periodo = $this->getModel();
        $this->periodo->fromArray($this->getData());
    
        $validator = new Enc_Validator_Periodo_DatosPrincipales();
        if (!$validator->isValid($this->periodo)) {
            return array(
                "status" => false,
                "errors" => $validator->getMessages(),
            );
        }
    
        return array("status" => true);
    }
    
    public function save()
    {
        // Guardar en la BD
        $this->periodo->save();
    
        // Retornar el ID de la plantilla modificada
        $id = $this->periodo->get('periodo_lectivo_id');
        return $id;
    }
}