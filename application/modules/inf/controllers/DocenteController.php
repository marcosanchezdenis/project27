<?php
class Inf_DocenteController extends Base_Controller
{
    const CODIGO_OPERACION = 'inf-doc';
    
    public function init()
    {
        parent::init();
    
        if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
            $this->goHomePage();
        }
    }
    
	public function indexAction()
	{
		$this->view->mainTitle = "Informes por Docentes";
		$perm = new Base_Helper_View_Permisos();
		$user = $perm->findLoggedUser();
		$roles = $user->getRoles();
		if(in_array('adm', $roles) || in_array('enc', $roles) || in_array('dir', $roles) || in_array('sec', $roles)){
			$docentes = $this->getRepository()->find();
			$this->view->docentes = $docentes;
		}else if(in_array('doc', $roles)){
			$docData = $user->Persona->Docente->getData();
			$docData = $docData[0];
			$docente_id = $docData->docente_id;
			$this->_redirect('/inf/docente/get?id='.$docente_id);
		}
	}
	
	public function getAction()
	{
	    $this->view->mainTitle = "Informes del Docente";
	     
	    $id = $this->getParam("id");
		$perm = new Base_Helper_View_Permisos();
		$user = $perm->findLoggedUser();
		$roles = $user->getRoles();
		if(in_array('adm', $roles) || in_array('enc', $roles) || in_array('dir', $roles) || in_array('sec', $roles)){
			$docente = $this->getRepository()->findById($id);
			$this->view->docente = $docente;
			$this->view->puedeGenerar = $this->puedeGenerarInforme();
		}else if(in_array('doc', $roles)){
			$docData = $user->Persona->Docente->getData();
			$docData = $docData[0];
			$docente_id = $docData->docente_id;
			if($id == $docente_id){
				$docente = $this->getRepository()->findByIdRestricted($id);
				$this->view->docente = $docente;
			}else{
				$this->_redirect('/inf/docente/get?id='.$docente_id);
			}
		}
	}
	
	public function getInformeData($id)
	{
	  //  $table = Doctrine_Core::getTable('Model_ResultadoDocente')->find();
	  //  $table->refresh($id);
	    $query = new Inf_QueryObject_Docente_InformeData($id);
	    $data  = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    return $data[0];
	}
	
	public function getEncuesta($id, $departamento, $dpto = null)
	{
	    $encuestaRepository = new Enc_Repository_Encuesta();
	    $encuesta = $encuestaRepository->findModelById($id);
	    $encuesta->refresh($id);
	    return $encuesta->getRespuestas($departamento, $dpto);
	}
	
	public function getPorcentaje($cntRespuestas)
	{
	    $respuestaRepository = new Resp_Repository_Encuesta();
	    $totalRespuestas = $respuestaRepository->getTotalRespuestas();
	    $porcentaje = 100 * ($cntRespuestas / $totalRespuestas);
	    return round($porcentaje, 2);
	}
	
	/*public function informeAction()
	{
	    $this->view->mainTitle = "Informe";
	    $enc_particular_id = $this->getParam("id");

		if($this->puedeVerInforme($enc_particular_id)){
			$enc_part = $this->getEncuesta($enc_particular_id, false);
			$this->view->data     = $this->getInformeData($enc_particular_id);
			$this->view->data['tipo'] = "docente";
			$this->view->puedeGenerar = $this->puedeGenerarInforme();
			$this->view->encuestaDepartamento = $this->getEncuesta($enc_particular_id, true, $this->view->data['departamento_id']);
			$this->view->idencuesta = $enc_particular_id;
			$this->view->encuesta = $enc_part;
			$this->view->porcentaje = $this->getPorcentaje($this->view->data['cnt_respuestas']);

			$this->view->introduccion = $this->view->render('docente/introduccion.phtml');
			$this->view->glosario	  = $this->view->render('docente/glosario.phtml');
			$this->view->perfil		  = $this->view->render('docente/perfil.phtml');
			$this->view->mediaDpto    = $this->view->render('docente/nota-media-dpto-backup.phtml');
			$this->view->notaDocente  = $this->view->render('docente/nota-docente-backup.phtml');
		}else{
			$this->_redirect('/inf/docente/index');
		}
	}*/

	public function puedeVerInforme($enc_part_id){
		$puedeVer = false;
		$perm = new Base_Helper_View_Permisos();
		$user = $perm->findLoggedUser();
		$roles = $user->getRoles();
		if(in_array('adm', $roles) || in_array('enc', $roles) || in_array('dir', $roles) || in_array('sec', $roles)){
			$puedeVer = true;
		}else if(in_array('doc', $user->getRoles())){
			$encuestaRepository = new Enc_Repository_Encuesta();
			$enc_part = $encuestaRepository->findModelById($enc_part_id);
			$docente_id_fromuser = $user->Persona->Docente->getData();
			$docente_id_fromuser = $docente_id_fromuser[0];
			$docente_id_fromuser = $docente_id_fromuser->docente_id;
			$docente_id_fromenc = $enc_part->Docente->docente_id;
			if($docente_id_fromuser == $docente_id_fromenc && $enc_part->PeriodoLectivo->visible_profesores == "S"){
				$puedeVer = true;
			}
		}

		return $puedeVer;
	}

	public function puedeGenerarInforme(){
		$puedeGenerar = false;
		$perm = new Base_Helper_View_Permisos();
		$user = $perm->findLoggedUser();
		$roles = $user->getRoles();
		if(in_array('adm', $roles) || in_array('enc', $roles) || in_array('dir', $roles) || in_array('sec', $roles)){
			$puedeGenerar = true;
		}

		return $puedeGenerar;
	}

	public function genInformeAction()
	{
		$enc_particular_id = $this->getParam("id");
		$encPart = new Enc_Repository_Encuesta();
		$encPart = $encPart->findModelById($enc_particular_id);

		if($this->puedeGenerarInforme()){
			//Borramos toda la generacion previa que hubo
			$repo = $this->getRepository();
			$repo->deleteGens($enc_particular_id);

			//Generamos los resultados de la encuesta
			$data     = $this->getInformeData($enc_particular_id);
			$encuesta = $this->getEncuesta($enc_particular_id, false);
			$encuestaDepartamento = $this->getEncuesta($enc_particular_id, true, $data['departamento_id']);

			$this->genXls($enc_particular_id, $data, $encuesta);
			$this->genPdf($enc_particular_id, $data, $encuesta);

			//Encuesta
			foreach($encuesta['Categorias'] as $categoria){
				$res = new Model_ResultadoDocente();
				$res->EncParticular = $encPart;
				$res->texto = $categoria['categoria'];
				$res->promedio = $categoria['promedio'];
				$res->nota = $categoria['nota'];
				$res->bold = false;
				$res->part = 1;
				$res->save();

				foreach($categoria['Categorias'] as $subCategoria){
					$res = new Model_ResultadoDocente();
					$res->EncParticular = $encPart;
					$res->texto = $subCategoria['categoria'];
					$res->promedio = $subCategoria['promedio'];
					$res->nota = $subCategoria['nota'];
					$res->bold = false;
					$res->part = 1;
					$res->save();
				}
			}

			foreach($encuesta['Categorias'] as $categoria){
				$res = new Model_ResultadoDocente();
				$res->EncParticular = $encPart;
				$res->texto = $categoria['categoria'];
				$res->promedio = $categoria['promedio'];
				$res->nota = $categoria['nota'];
				$res->bold = true;
				$res->part = 2;
				$res->save();

				foreach($categoria['Preguntas'] as $pregunta){
					$res = new Model_ResultadoDocente();
					$res->EncParticular = $encPart;
					$res->texto = $pregunta['pregunta'];
					$res->promedio = $pregunta['promedio'];
					$res->nota = $pregunta['nota'];
					$res->bold = false;
					$res->part = 2;
					$res->save();
				}

				foreach($categoria['Categorias'] as $subCategoria){
					$res = new Model_ResultadoDocente();
					$res->EncParticular = $encPart;
					$res->texto = $subCategoria['categoria'];
					$res->promedio = $subCategoria['promedio'];
					$res->nota = $subCategoria['nota'];
					$res->bold = false;
					$res->part = 2;
					$res->save();

					foreach($subCategoria['Preguntas'] as $pregunta){
						$res = new Model_ResultadoDocente();
						$res->EncParticular = $encPart;
						$res->texto = $pregunta['pregunta'];
						$res->promedio = $pregunta['promedio'];
						$res->nota = $pregunta['nota'];
						$res->bold = false;
						$res->part = 2;
						$res->save();
					}
				}
			}

			//Media del departamento
			foreach($encuestaDepartamento['Categorias'] as $categoria){
				$res = new Model_ResultadoDocente();
				$res->EncParticular = $encPart;
				$res->texto = $categoria['categoria'];
				$res->promedio = $categoria['promedio'];
				$res->nota = $categoria['nota'];
				$res->bold = false;
				$res->part = 3;
				$res->save();

				foreach($categoria['Categorias'] as $subCategoria){
					$res = new Model_ResultadoDocente();
					$res->EncParticular = $encPart;
					$res->texto = $subCategoria['categoria'];
					$res->promedio = $subCategoria['promedio'];
					$res->nota = $subCategoria['nota'];
					$res->bold = false;
					$res->part = 3;
					$res->save();
				}
			}

			foreach($encuestaDepartamento['Categorias'] as $categoria){
				$res = new Model_ResultadoDocente();
				$res->EncParticular = $encPart;
				$res->texto = $categoria['categoria'];
				$res->promedio = $categoria['promedio'];
				$res->nota = $categoria['nota'];
				$res->bold = true;
				$res->part = 4;
				$res->save();

				foreach($categoria['Preguntas'] as $pregunta){
					$res = new Model_ResultadoDocente();
					$res->EncParticular = $encPart;
					$res->texto = $pregunta['pregunta'];
					$res->promedio = $pregunta['promedio'];
					$res->nota = $pregunta['nota'];
					$res->bold = false;
					$res->part = 4;
					$res->save();
				}

				foreach($categoria['Categorias'] as $subCategoria){
					$res = new Model_ResultadoDocente();
					$res->EncParticular = $encPart;
					$res->texto = $subCategoria['categoria'];
					$res->promedio = $subCategoria['promedio'];
					$res->nota = $subCategoria['nota'];
					$res->bold = false;
					$res->part = 4;
					$res->save();

					foreach($subCategoria['Preguntas'] as $pregunta){
						$res = new Model_ResultadoDocente();
						$res->EncParticular = $encPart;
						$res->texto = $pregunta['pregunta'];
						$res->promedio = $pregunta['promedio'];
						$res->nota = $pregunta['nota'];
						$res->bold = false;
						$res->part = 4;
						$res->save();
					}
				}
			}

			//Vemos la encuesta
			$this->_redirect('/inf/docente/ver-informe?id='.$enc_particular_id);
		}
	}


	public function verInformeAction()
	{
		$enc_particular_id = $this->getParam("id");

		if($this->puedeVerInforme($enc_particular_id)){
			$query = new Inf_QueryObject_Docente_ExistGen($enc_particular_id);
			$data  = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
			if(intval($data[0]["cant"]) > 0){
				$this->view->mainTitle = "Informe";
				$this->view->data     = $this->getInformeData($enc_particular_id);
				$this->view->data['tipo'] = "docente";
				$this->view->data['id'] = $enc_particular_id;
				$this->view->porcentaje = $this->getPorcentaje($this->view->data['cnt_respuestas']);
				$res = new Inf_Repository_Docente();
				$this->view->results = $res->listResultsByEncPart($enc_particular_id);

				$this->view->introduccion = $this->view->render('docente/introduccion.phtml');
				$this->view->glosario	  = $this->view->render('docente/glosario.phtml');
				$this->view->perfil		  = $this->view->render('docente/perfil.phtml');
				$this->view->mediaDpto    = $this->view->render('docente/nota-media-dpto.phtml');
				$this->view->notaDocente  = $this->view->render('docente/nota-docente.phtml');
			}else{
				echo "Aún no se ha generado la encuesta. <br/><a href='/inf/docente/gen-informe?id=".$enc_particular_id."'>Click aquí para generar la encuesta</a>";
			}
		}
	}

	public function getRepository()
	{
		return new Inf_Repository_Docente();
	}

	public function infXlsAction()
	{
		$enc_particular_id = $this->getParam("id");

		if($this->puedeVerInforme($enc_particular_id)){
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);

			$filePath = __DIR__.'/../../../../public/xls/docente/'.$enc_particular_id.'.xls';
			if(file_exists($filePath)){
				//$this->_redirect('/xls/docente/'.$enc_particular_id.'.xls');
				header("Cache-Control: no-cache, must-revalidate");
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: inline; filename="infdocente.xls"');
				readfile($filePath);
			}else{
				echo "No se ha generado el archivo XLS. For favor vuelva a generar la encuesta.";
			}
		}else{
			echo "No estás autorizado para ver el informe.";
		}
	}

	public function infPdfAction()
	{
		$enc_particular_id = $this->getParam("id");

		if($this->puedeVerInforme($enc_particular_id)){
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);

			$filePath = __DIR__.'/../../../../public/pdf/docente/'.$enc_particular_id.'.pdf';
			if(file_exists($filePath)){
				//$this->_redirect('/pdf/docente/'.$enc_particular_id.'.pdf');
				header("Cache-Control: no-cache, must-revalidate");
				header('Content-type: application/xls');
				header('Content-Disposition: inline; filename="infdocente.pdf"');
				readfile($filePath);
			}else{
				echo "No se ha generado el archivo PDF. For favor vuelva a generar la encuesta.";
			}
		}else{
			echo "No estás autorizado para ver el informe.";
		}
	}

	public function genXls($enc_particular_id, $data, $encuestaDepartamento)
	{
		$porcentaje = $this->getPorcentaje($this->view->data['cnt_respuestas']);

		require_once 'PHPExcel.php';

		$fila = 1;

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("DEI-LED")
			->setLastModifiedBy("DEI-LED")
			->setTitle("Informe Docente")
			->setSubject("Encuestas del Docente")
			->setDescription("Evaluacion del Docente")
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
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(false)->setSize(11);

		$hoy = new \DateTime();

		$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(0, $fila++, $data['departamento'])
			->setCellValueByColumnAndRow(0, $fila++, $data['docente'])
			->setCellValueByColumnAndRow(0, $fila++, $data['asignatura'])
			->setCellValueByColumnAndRow(0, $fila++, $data['periodo'])
			->setCellValueByColumnAndRow(0, $fila++, "Fecha de Generación: ".$hoy->format('d/m/Y'))
		->setCellValueByColumnAndRow(0, $fila++, 'Respuestas recibidas: '.$data['cnt_respuestas'].' ('.$porcentaje.'% del universo)');

		$fila += 2;

		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':C'.$fila)->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':C'.$fila)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(0, $fila, 'GRANDES CATEGORIAS (puntuacion del 1 al 4)')
			->setCellValueByColumnAndRow(1, $fila, 'PROMEDIO')
			->setCellValueByColumnAndRow(2, $fila++, 'NOTA');

		foreach($encuestaDepartamento['Categorias'] as $categoria){
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

		foreach($encuestaDepartamento['Categorias'] as $categoria){
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

		$objPHPExcel->getActiveSheet()->setTitle('Docente');
		$objPHPExcel->setActiveSheetIndex(0);

		/*
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="docente.xls"');
		header('Cache-Control: max-age=0');

		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: cache, max-age=1, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		*/

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save('php://output');

		$objWriter->save(__DIR__.'/../../../../public/xls/docente/'.$enc_particular_id.'.xls');

	}

	public function genPdf($enc_particular_id, $data, $encuesta)
	{
		$this->view->data = $data;
		$this->view->data['tipo'] = "docente";
		$this->view->encuesta = $encuesta;
		$this->view->porcentaje = $this->getPorcentaje($data['cnt_respuestas']);

		$html = $this->view->render('docente/inf-pdf.phtml');

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
			"Informe de Encuesta a Docente\n" . $this->view->data['periodo'] .
			"\nFecha de Generación: ".$hoy->format('d/m/Y'),
			array(0,64,255), array(0,64,128)
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
		$pdf->Output(__DIR__.'/../../../../public/pdf/docente/'.$enc_particular_id.'.pdf', 'F');
	}

}
