<?php
class Resp_Repository_Encuesta extends Base_Repository
{
    /**
     * 
     * @param string $serial
     * @throws Exception
     * @return Model_EncParticular
     */
 	public function findBySerial($serial)
	{
		$encuesta = Doctrine_Core::getTable("Model_EncParticular")->findOneBy('identificador', $serial);
    	
    	if ($encuesta === null) {
    		throw new Exception("No se encontr&oacute; la encuesta");
    	}
    	
    	return $encuesta;
	}
	
	public function getTotalRespuestas()
	{
	    $query = Doctrine_Query::create()
	        ->addSelect("count(1) as cantidad")
	        ->from("Model_Respuesta r");
	    $cantidad = $query->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	    return $cantidad;
	}

	public function getCantRespByEnc($idenc)
	{
		$query = Doctrine_Query::create()
			->addSelect("count(r.respuesta_id) as cnt_respuestas")
			->from("Model_EncParticular ep")
			->innerJoin("ep.Respuesta r")
			->where("ep.enc_particular_id = ?", $idenc);
		$cantidad = $query->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
		return $cantidad;
	}

	public function estaEnRangoFecha($idenc, $fecini, $fecfin)
	{
		$whereIni = "";
		$whereFin = "";

		if((!is_null($fecini)) && $fecini != ""){
			$whereIni .= "NOW() >= ep.fecha_inicio";
		}else{
			$whereIni .= "ep.enc_particular_id > 0";
		}

		if((!is_null($fecfin)) && $fecfin != ""){
			$whereFin .= "NOW() <= ep.fecha_fin";
		}else{
			$whereFin .= "ep.enc_particular_id > 0";
		}

		$query = Doctrine_Query::create()
			->addSelect("count(ep.enc_particular_id)")
			->from("Model_EncParticular ep")
			->addWhere("ep.enc_particular_id = ?", $idenc)
			->andWhere($whereIni)
			->andWhere($whereFin);
		$cantidad = $query->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

		if($cantidad == 1)
			return true;

		return false;
	}
}