<?php
class Inf_MateriaController extends Base_Controller
{
	const CODIGO_OPERACION = 'inf-mat';

	public function init()
	{
		parent::init();

		if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
			$this->goHomePage();
		}
	}

	public function indexAction()
	{
		$this->view->mainTitle = "Informes por Materia";
		$repo = new Ins_Repository_Asignatura();
		$asignaturas = $repo->find();
		$this->view->asignaturas = $asignaturas;
	}

	public function setPeriodoAction()
	{
		$this->view->mainTitle = "Informes por Materia";

		$idmateria = $this->getParam("materia");
		$repoAsig = new Ins_Repository_Asignatura();
		$repoEnc = new Enc_Repository_Encuesta();

		$this->view->asignatura = $repoAsig->findById($idmateria);
		$this->view->periodos = $repoEnc->getPeriodos();
	}

	public function getInformeData($id)
	{
		$query = new Inf_QueryObject_Docente_InformeData($id);
		$data  = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $data[0];
	}

	public function getEncuesta($id, $departamento, $dpto = null)
	{
		$encuestaRepository = new Enc_Repository_Encuesta();
		$encuesta = $encuestaRepository->findModelById($id);
		return $encuesta->getRespuestas($departamento, $dpto);
	}

	public function getPorcentaje($cntRespuestas)
	{
		$respuestaRepository = new Resp_Repository_Encuesta();
		$totalRespuestas = $respuestaRepository->getTotalRespuestas();
		$porcentaje = 100 * ($cntRespuestas / $totalRespuestas);
		return round($porcentaje, 2);
	}

	public function averageAllResults($encuestas)
	{
		$list = array();
		foreach($encuestas as $enc_particular){
			$list[] = $this->getEncuesta($enc_particular["enc_particular_id"], false);
		}

		$cant = count($list);
		$data = $this->getInformeData($encuestas[0]["enc_particular_id"]);
		$cantRespuestas = $data['cnt_respuestas'];

		for($i=1; $i<$cant; $i++){
			$tmp = $this->getInformeData($encuestas[$i]['enc_particular_id']);
			$cantRespuestas = $cantRespuestas + $tmp['cnt_respuestas'];
		}

		$averageEnc = $list[0];
		for($i=1; $i<$cant; $i++){
			foreach ($averageEnc['Categorias'] as &$cateAvg){
				$pregCateAvg['$cateAvg'] = "";

				//Buscamos que se repita el nombre
				foreach ($list[$i]['Categorias'] as &$tmpCate){
					if(strcmp($this->normStr($tmpCate['categoria']), $this->normStr($cateAvg['categoria'])) == 0){
						if(strpos($tmpCate['nota'], '*') !== FALSE){ //si tiene un *
							$cateAvg['asterisk'] = "*";
						}
						$cateAvg['promedio'] += $tmpCate['promedio'];
					}
				}

				foreach ($cateAvg['Preguntas'] as &$pregCateAvg){
					$pregCateAvg['asterisk'] = "";

					//Buscamos que se repita el nombre
					foreach ($list[$i]['Categorias'] as &$tmpCate){
						foreach ($tmpCate['Preguntas'] as &$tmpCatePreg){
							if(strcmp($this->normStr($pregCateAvg['pregunta']), $this->normStr($tmpCatePreg['pregunta'])) == 0){
								if(strpos($tmpCatePreg['nota'], '*') !== FALSE){ //si tiene un *
									$pregCateAvg['asterisk'] = "*";
								}
								$pregCateAvg['promedio'] += $tmpCatePreg['promedio'];
							}
						}
					}
				}

				foreach ($cateAvg['Categorias'] as &$subCateAvg){
					$subCateAvg['asterisk'] = "";

					//Buscamos que se repita el nombre
					foreach ($list[$i]['Categorias'] as &$tmpCate){
						foreach ($tmpCate['Categorias'] as &$tmpSubCate){
							if(strcmp($this->normStr($tmpSubCate['categoria']), $this->normStr($subCateAvg['categoria'])) == 0){
								if(strpos($tmpSubCate['nota'], '*') !== FALSE){ //si tiene un *
									$subCateAvg['asterisk'] = "*";
								}
								$subCateAvg['promedio'] += $tmpSubCate['promedio'];
							}
						}
					}

					foreach ($subCateAvg['Preguntas'] as &$subPregCateAvg){
						$subPregCateAvg['asterisk'] = "";

						//Buscamos que se repita el nombre
						foreach ($list[$i]['Categorias'] as &$tmpCate){
							foreach ($tmpCate['Categorias'] as &$tmpSubCate){
								foreach ($tmpSubCate['Preguntas'] as &$tmpSubCatePreg){
									if(strcmp($this->normStr($tmpSubCatePreg['pregunta']), $this->normStr($subPregCateAvg['pregunta'])) == 0){
										if(strpos($tmpSubCatePreg['nota'], '*') !== FALSE){ //si tiene un *
											$subPregCateAvg['asterisk'] = "*";
										}
										$subPregCateAvg['promedio'] += $tmpSubCatePreg['promedio'];
									}
								}
							}
						}
					}
				}
			}
		}

		foreach ($averageEnc['Categorias'] as &$cateAvg){
			$cateAvg['promedio'] = $cateAvg['promedio'] / $cant;
			$cateAvg['nota'] = $this->getNotaByValor($cateAvg['promedio']).$cateAvg['asterisk'];

			foreach ($cateAvg['Preguntas'] as &$pregCateAvg){
				$pregCateAvg['promedio'] = $pregCateAvg['promedio'] / $cant;
				$pregCateAvg['nota'] = $this->getNotaByValor($pregCateAvg['promedio']).$pregCateAvg['asterisk'];
			}

			foreach ($cateAvg['Categorias'] as &$subCateAvg){
				$subCateAvg['promedio'] = $subCateAvg['promedio'] / $cant;
				$subCateAvg['nota'] = $this->getNotaByValor($subCateAvg['promedio']).$subCateAvg['asterisk'];

				foreach ($subCateAvg['Preguntas'] as &$subPregCateAvg){
					$subPregCateAvg['promedio'] = $subPregCateAvg['promedio'] / $cant;
					$subPregCateAvg['nota'] = $this->getNotaByValor($subPregCateAvg['promedio']).$subPregCateAvg['asterisk'];
				}
			}
		}

		return array($averageEnc, $data, $this->getPorcentaje($cantRespuestas), $cantRespuestas, $cant);
	}

	public function getNotaByValor($valor){
		if($valor <= 2.0){
			return "Deficitario";
		}else if($valor <= 2.6){
			return "Insuficiente";
		}else if($valor <= 3.2){
			return "Aceptable";
		}else if($valor <= 3.7){
			return "Bueno";
		}else{
			return "Excelente";
		}
	}

	public function normStr($string){
		return preg_replace('/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $string);
	}

	public function informeAction()
	{
		$action = $this->getParam("act");

		$materia_id = $this->getParam("materia_id");
		$perlec_id = $this->getParam("periodo_lectivo_id");

		if($action == "Ver Informe"){
			$this->_redirect('/inf/materia/ver-informe?materia_id='.$materia_id.'&periodo_lectivo_id='.$perlec_id);
		}else if($action == "Generar"){
			$this->_redirect('/inf/materia/gen-informe?materia_id='.$materia_id.'&periodo_lectivo_id='.$perlec_id);
		}else{
			$this->_redirect('/inf/materia/index');
		}

		/*$this->view->mainTitle = "Informe";

		$materia_id = $this->getParam("materia_id");
		$perlec_id = $this->getParam("periodo_lectivo_id");

		$encuestas = $this->getRepository()->getListEncuestas($materia_id, $perlec_id);
		$results = $this->averageAllResults($encuestas);

		$this->view->encuesta   = $results[0];
		$this->view->data       = $results[1];
		$this->view->porcentaje = $results[2];
		$this->view->cant_resp = $results[3];
		$this->view->cant_mat = $results[4];

		$this->view->asignatura_id = $materia_id;
		$this->view->periodo_id = $perlec_id;

		$this->view->introduccion = $this->view->render('materia/introduccion.phtml');
		$this->view->glosario	  = $this->view->render('materia/glosario.phtml');
		$this->view->perfil		  = $this->view->render('materia/perfil.phtml');
		$this->view->notaMateria  = $this->view->render('materia/nota-materia-backup.phtml');*/
	}

	public function genInformeAction()
	{
		$materia_id = $this->getParam("materia_id");
		$perlec_id = $this->getParam("periodo_lectivo_id");

		$mat = new Ins_Repository_Asignatura();
		$mat = $mat->findModelById($materia_id);

		$perlec = new Enc_Repository_Periodo();
		$perlec = $perlec->findModelById($perlec_id);

		//Borramos toda la generacion previa que hubo
		$repo = $this->getRepository();
		$repo->deleteGens($materia_id, $perlec_id);

		//Generamos los resultados de la encuesta
		$encuestas = $this->getRepository()->getListEncuestas($materia_id, $perlec_id);
		$results = $this->averageAllResults($encuestas);

		$encuesta = $results[0];

		$this->genXls($materia_id, $perlec_id, $results);
		$this->genPdf($materia_id, $perlec_id, $results);

		foreach($encuesta['Categorias'] as $categoria){
			$res = new Model_ResultadoMateria();
			$res->Asignatura = $mat;
			$res->PeriodoLectivo = $perlec;
			$res->texto = $categoria['categoria'];
			$res->promedio = $categoria['promedio'];
			$res->nota = $categoria['nota'];
			$res->bold = false;
			$res->part = 1;
			$res->save();

			foreach($categoria['Categorias'] as $subCategoria){
				$res = new Model_ResultadoMateria();
				$res->Asignatura = $mat;
				$res->PeriodoLectivo = $perlec;
				$res->texto = $subCategoria['categoria'];
				$res->promedio = $subCategoria['promedio'];
				$res->nota = $subCategoria['nota'];
				$res->bold = false;
				$res->part = 1;
				$res->save();
			}
		}

		foreach($encuesta['Categorias'] as $categoria){
			$res = new Model_ResultadoMateria();
			$res->Asignatura = $mat;
			$res->PeriodoLectivo = $perlec;
			$res->texto = $categoria['categoria'];
			$res->promedio = $categoria['promedio'];
			$res->nota = $categoria['nota'];
			$res->bold = true;
			$res->part = 2;
			$res->save();

			foreach($categoria['Preguntas'] as $pregunta){
				$res = new Model_ResultadoMateria();
				$res->Asignatura = $mat;
				$res->PeriodoLectivo = $perlec;
				$res->texto = $pregunta['pregunta'];
				$res->promedio = $pregunta['promedio'];
				$res->nota = $pregunta['nota'];
				$res->bold = false;
				$res->part = 2;
				$res->save();
			}

			foreach($categoria['Categorias'] as $subCategoria){
				$res = new Model_ResultadoMateria();
				$res->Asignatura = $mat;
				$res->PeriodoLectivo = $perlec;
				$res->texto = $subCategoria['categoria'];
				$res->promedio = $subCategoria['promedio'];
				$res->nota = $subCategoria['nota'];
				$res->bold = false;
				$res->part = 2;
				$res->save();

				foreach($subCategoria['Preguntas'] as $pregunta){
					$res = new Model_ResultadoMateria();
					$res->Asignatura = $mat;
					$res->PeriodoLectivo = $perlec;
					$res->texto = $pregunta['pregunta'];
					$res->promedio = $pregunta['promedio'];
					$res->nota = $pregunta['nota'];
					$res->bold = false;
					$res->part = 2;
					$res->save();
				}
			}
		}

		//Vemos la encuesta
		$this->_redirect('/inf/materia/ver-informe?materia_id='.$materia_id.'&periodo_lectivo_id='.$perlec_id);
	}

	public function verInformeAction()
	{
		$materia_id = $this->getParam("materia_id");
		$perlec_id = $this->getParam("periodo_lectivo_id");

		$query = new Inf_QueryObject_Materia_ExistGen($materia_id, $perlec_id);
		$data  = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		if(intval($data[0]["cant"]) > 0){
			$encuestas = $this->getRepository()->getListEncuestas($materia_id, $perlec_id);
			$cant = count($encuestas);
			$data = $this->getInformeData($encuestas[0]["enc_particular_id"]);
			$cantRespuestas = $data['cnt_respuestas'];

			for($i=1; $i<$cant; $i++){
				$tmp = $this->getInformeData($encuestas[$i]['enc_particular_id']);
				$cantRespuestas = $cantRespuestas + $tmp['cnt_respuestas'];
			}

			$this->view->mainTitle = "Informe";
			$this->view->asignatura_id = $materia_id;
			$this->view->periodo_id = $perlec_id;
			$this->view->data     = $data;
			$this->view->data['tipo'] = "materia";
			$this->view->cant_mat = $cant;
			$this->view->porcentaje = $this->getPorcentaje($cantRespuestas);
			$res = new Inf_Repository_Materia();
			$this->view->results = $res->listResultsByEncPart($materia_id, $perlec_id);

			$this->view->introduccion = $this->view->render('materia/introduccion.phtml');
			$this->view->glosario	  = $this->view->render('materia/glosario.phtml');
			$this->view->perfil		  = $this->view->render('materia/perfil.phtml');
			$this->view->notaMateria    = $this->view->render('materia/nota-materia.phtml');
		}else{
			echo "Aún no se ha generado la encuesta. <br/><a href='/inf/materia/gen-informe?materia_id=".$materia_id."&periodo_lectivo_id=".$perlec_id."'>Click aquí para generar la encuesta</a>";
		}
	}

	public function infXlsAction()
	{
		$materia_id = $this->getParam("materia_id");
		$perlec_id = $this->getParam("periodo_lectivo_id");

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if(file_exists(__DIR__.'/../../../../public/xls/materia/'.$materia_id.'_'.$perlec_id.'.xls')){
			$this->_redirect('/xls/materia/'.$materia_id.'_'.$perlec_id.'.xls');
		}else{
			echo "No se ha generado el archivo XLS. For favor vuelva a generar la encuesta.";
		}
	}

	public function infPdfAction()
	{
		$materia_id = $this->getParam("materia_id");
		$perlec_id = $this->getParam("periodo_lectivo_id");

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if(file_exists(__DIR__.'/../../../../public/pdf/materia/'.$materia_id.'_'.$perlec_id.'.pdf')){
			$this->_redirect('/pdf/materia/'.$materia_id.'_'.$perlec_id.'.pdf');
		}else{
			echo "No se ha generado el archivo PDF. For favor vuelva a generar la encuesta.";
		}
	}

	public function genXls($materia_id, $perlec_id, $results)
	{
		$encuesta   = $results[0];
		$data       = $results[1];
		$porcentaje = $results[2];
		$cant_resp = $results[3];
		$cant_mat = $results[4];

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		require_once 'PHPExcel.php';

		$fila = 1;

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("DEI-LED")
			->setLastModifiedBy("DEI-LED")
			->setTitle("Informe Docente")
			->setSubject("Encuesta de Asignatura")
			->setDescription("Evaluacion por Asignatura")
			->setKeywords("encuesta estadistica universidad")
			->setCategory("Educacion");

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);

		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('A6:C6');
		$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(false)->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(false)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(false)->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(false)->setSize(11);

		$hoy = new \DateTime();

		$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(0, $fila++, $data['departamento'])
			->setCellValueByColumnAndRow(0, $fila++, $data['asignatura'])
			->setCellValueByColumnAndRow(0, $fila++, $data['periodo'])
			->setCellValueByColumnAndRow(0, $fila++, 'Cursos promediados: '. $cant_mat)
			->setCellValueByColumnAndRow(0, $fila++, "Fecha de Generación: ".$hoy->format('d/m/Y'))
			->setCellValueByColumnAndRow(0, $fila++, 'Respuestas recibidas: '.$cant_resp.' ('.$porcentaje.'% del universo)');


		$fila += 2;

		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':C'.$fila)->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':C'.$fila)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(0, $fila, 'GRANDES CATEGORIAS (puntuacion del 1 al 4)')
			->setCellValueByColumnAndRow(1, $fila, 'PROMEDIO')
			->setCellValueByColumnAndRow(2, $fila++, 'NOTA');

		foreach($encuesta['Categorias'] as $categoria){
			$objPHPExcel->getActiveSheet()
				->setCellValueByColumnAndRow(0, $fila, $categoria['categoria'])
				->setCellValueByColumnAndRow(1, $fila, $categoria['promedio'])
				->setCellValueByColumnAndRow(2, $fila++, $categoria['nota']);

			foreach($categoria['Categorias'] as $subCategoria){
				$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow(0, $fila, $subCategoria['categoria'])
					->setCellValueByColumnAndRow(1, $fila, $subCategoria['promedio'])
					->setCellValueByColumnAndRow(2, $fila++, $subCategoria['nota']);
			}
		}

		$fila += 2;

		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':C'.$fila)->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':C'.$fila)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(0, $fila, 'GRANDES CATEGORIAS (puntuacion del 1 al 4)')
			->setCellValueByColumnAndRow(1, $fila, 'PROMEDIO')
			->setCellValueByColumnAndRow(2, $fila++, 'NOTA');

		foreach($encuesta['Categorias'] as $categoria){
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$fila.':C'.$fila);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getFont()->setBold(true)->setSize(12);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila++, $categoria['categoria']);

			foreach($categoria['Preguntas'] as $pregunta){
				$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow(0, $fila, $pregunta['pregunta'])
					->setCellValueByColumnAndRow(1, $fila, $pregunta['promedio'])
					->setCellValueByColumnAndRow(2, $fila++, $pregunta['nota']);
			}

			foreach($categoria['Categorias'] as $subCategoria){
				$objPHPExcel->getActiveSheet()->mergeCells('A'.$fila.':C'.$fila);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getFont()->setBold(true)->setSize(12);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila++, $subCategoria['categoria']);

				foreach($subCategoria['Preguntas'] as $pregunta){
					$objPHPExcel->getActiveSheet()
						->setCellValueByColumnAndRow(0, $fila, $pregunta['pregunta'])
						->setCellValueByColumnAndRow(1, $fila, $pregunta['promedio'])
						->setCellValueByColumnAndRow(2, $fila++, $pregunta['nota']);
				}
			}
		}

		$objPHPExcel->getActiveSheet()->getStyle('B1:C'.$fila)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->getStyle('A1:C'.$fila)->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->setTitle('Asignatura');
		$objPHPExcel->setActiveSheetIndex(0);

		/*
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="asignatura.xls"');
		header('Cache-Control: max-age=0');

		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: cache, max-age=1, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		*/

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save('php://output');

		$objWriter->save(__DIR__.'/../../../../public/xls/materia/'.$materia_id.'_'.$perlec_id.'.xls');
	}

	public function genPdf($materia_id, $perlec_id, $results)
	{
		$this->view->mainTitle = "Informe";

        $data = $results[1];
		$this->view->encuesta   = $results[0];
		$this->view->data       = $results[1];
		$this->view->porcentaje = $results[2];
		$this->view->cant_resp = $results[3];
		$this->view->cant_mat = $results[4];

		$this->view->asignatura_id = $materia_id;
		$this->view->periodo_id = $perlec_id;

		$html = $this->view->render('materia/inf-pdf.phtml');

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		// Include the main TCPDF library (search for installation path).
		require_once('tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator('DEI-LED');
		$pdf->SetAuthor('DEI-LED');
		$pdf->SetTitle('Informe de Encuesta');
		$pdf->SetSubject('Encuesta de Docente');
		$pdf->SetKeywords('Encuesta, Informe, Docente');

		$hoy = new \DateTime();

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Facultad de Ciencias y Tecnologías',
			"Informe de Encuesta a Docente\n" . $data["periodo"] .
			"\nFecha de Generación: ".$hoy->format('d/m/Y')
			, array(0,64,255), array(0,64,128)
		);
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		$pdf->SetFont('dejavusans', '', 10, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		//$pdf->Output('informe_docente.pdf', 'I');
		$pdf->Output(__DIR__.'/../../../../public/pdf/materia/'.$materia_id.'_'.$perlec_id.'.pdf', 'F');
	}

	public function getRepository()
	{
		return new Inf_Repository_Materia();
	}
}
