<?php
class Inf_BrutoController extends Base_Controller
{
	const CODIGO_OPERACION = 'inf-bru';

	public function init()
	{
		parent::init();

		if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
			$this->goHomePage();
		}
	}

	public function indexAction()
	{
		$this->view->mainTitle = "Informes de Datos en Bruto";
		$repoEnc = new Enc_Repository_Encuesta();
		$this->view->periodos = $repoEnc->getPeriodos();
	}

	public function informeAction()
	{
		$perlec_id = $this->getParam("periodo_lectivo_id");
		$pdo = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
		$query = "SELECT ENCPART.enc_particular_id AS encid, ENC.nombre AS encnombre, ENC.fecha AS encfecha,
						(CAST(PER.periodo AS text)|| ' del ' ||CAST(PERLEC.anho_lectivo AS text)) AS periodo,
						 ASIG.nombre AS asignatura, CAR.nombre AS carrera, DEPTO.nombre AS departamento,
						 CATE.nombre AS categoria, PREG.pregunta AS pregunta,
						 PREG.tipo AS tipopregunta, PREG.es_obligatoria AS pregobl, getNombreSMByPregSMID(OPCELE.pregunta_sm_id) AS nomselmul,
						 getValorByEscalaValorID(RESPDET.escala_valor_id) AS valescala, getDescByEscalaValorID(RESPDET.escala_valor_id) AS descescala,
						 RESPDET.respuesta AS respuesta

FROM encuesta AS ENC

FULL OUTER JOIN enc_particular AS ENCPART
ON ENC.encuesta_id = ENCPART.encuesta_id

FULL OUTER JOIN periodo_lectivo AS PERLEC
ON ENCPART.periodo_lectivo_id = PERLEC.periodo_lectivo_id

FULL OUTER JOIN periodo AS PER
ON PERLEC.periodo_id = PER.periodo_id

FULL OUTER JOIN asignatura AS ASIG
ON ENCPART.asignatura_id = ASIG.asignatura_id

FULL OUTER JOIN carrera AS CAR
ON ASIG.carrera_id = CAR.carrera_id

FULL OUTER JOIN departamento AS DEPTO
ON CAR.departamento_id = DEPTO.departamento_id

FULL OUTER JOIN categoria AS CATE
ON ENC.encuesta_id = CATE.encuesta_id

FULL OUTER JOIN pregunta AS PREG
ON CATE.categoria_id = PREG.categoria_id

FULL OUTER JOIN resp_detalle as RESPDET
ON PREG.pregunta_id = RESPDET.pregunta_id

FULL OUTER JOIN opcion_elegida AS OPCELE
ON RESPDET.resp_detalle_id = OPCELE.resp_detalle_id

WHERE PERLEC.periodo_lectivo_id = :perlec

ORDER BY ENCPART.enc_particular_id, pregunta ASC;";

		$stmt = $pdo->prepare($query);

		$params = array(
			"perlec"  => "" . $perlec_id
		);
		$stmt->execute($params);

		$results = $stmt->fetchAll();

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		require_once 'PHPExcel.php';

		$fila = 1;

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("DEI-LED")
			->setLastModifiedBy("DEI-LED")
			->setTitle("Datos en Bruto")
			->setSubject("Datos por Periodo Lectivo")
			->setDescription("Datos de Encuestas")
			->setKeywords("encuesta estadistica universidad")
			->setCategory("Educacion");

		$objPHPExcel->setActiveSheetIndex(0);

		$colNames = array(
			"encid" => "Nº Encuesta","encnombre" => "Nombre Encuesta", "encfecha" => "Fecha Encuesta",
			"periodo" => "Periodo", "asignatura" => "Asignatura", "carrera" => "Carrera",
			"departamento" => "Departamento", "categoria" => "Categoría",
			"pregunta" => "Pregunta", "tipopregunta" => "Tipo Pregunta", "pregobl" => "Es Obligatoria",
			"nomselmul" => "Respuesta Selección Múltiple", "valescala" => "Valor de Escala",
			"descescala" => "Descripción del Valor de Escala", "respuesta" => "Respuesta"
		);

		$col = 0;
		foreach($colNames as $titleName){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $fila, $titleName);
		}

		$fila++;

		foreach($results as $item){
			$col = 0;
			foreach($item as $key => $value){
				if(array_key_exists($key, $colNames))
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $fila, $value);
			}
			$fila++;
		}

		$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->setTitle('Datos en Bruto');

		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

		foreach(range('A','O') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="datosbruto.xls"');
		header('Cache-Control: max-age=0');

		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: cache, max-age=1, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}