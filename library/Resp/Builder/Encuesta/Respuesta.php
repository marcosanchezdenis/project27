<?php
class Resp_Builder_Encuesta_Respuesta extends Base_Builder
{
    /**
     * @var Model_Respuesta
     */
    protected $respuesta;
    
    protected $encuesta;
    protected $respuestas;
    
    public function __construct($data, $repository)
    {
        parent::__construct($data, $repository);
        $this->encuesta   = $this->data["enc_particular_id"];
        $this->respuestas = $this->data["respuestas"];
    }
    
    public function getTodayDate()
    {
        return date('Y-m-d');
    }
    
    public function generateModels()
    {
        $this->respuesta = new Model_Respuesta();
        $this->respuesta->enc_particular_id = $this->encuesta;
        $this->respuesta->fecha = $this->getTodayDate();
        
        $this->responderPregAbiertas();
        $this->responderPregEscala();
        $this->responderPregSm();
        
        return array("status" => true);
    }
    
    protected function responderPregAbiertas()
    {
        foreach ($this->respuestas["A"] as $pregunta_id => $valor) {
            $detalle = new Model_RespDetalle();
            $detalle->pregunta_id = $pregunta_id;
            $detalle->respuesta   = $valor;
            
            $this->respuesta->RespDetalle[] = $detalle;
        }
    }
    
    protected function responderPregEscala()
    {
        foreach ($this->respuestas["E"] as $pregunta_id => $valor) {
            $detalle = new Model_RespDetalle();
            $detalle->pregunta_id     = $pregunta_id;
			if((!is_null($valor)) && $valor != "none"){
				$detalle->escala_valor_id = $valor;
			}else{
				$detalle->escala_valor_id = null;
			}

            $this->respuesta->RespDetalle[] = $detalle;
        }
    }
    
    protected function responderPregSm()
    {
        foreach ($this->respuestas["S"] as $pregunta_id => $valores) {
            $detalle = new Model_RespDetalle();
            $detalle->pregunta_id = $pregunta_id;
            
            foreach ($valores as $valor) {
                $sm = new Model_OpcionElegida();
                $sm->pregunta_sm_id = $valor;
                $detalle->OpcionElegida[] = $sm;
            }
            
            $this->respuesta->RespDetalle[] = $detalle;
        }
    }
    
    public function save()
    {
        $this->respuesta->save();
        $id = $this->respuesta->get('respuesta_id');
        return $id;
    }
}