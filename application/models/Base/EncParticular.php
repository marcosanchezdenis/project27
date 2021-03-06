<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_EncParticular', 'doctrine');

/**
 * Model_Base_EncParticular
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $enc_particular_id
 * @property integer $periodo_lectivo_id
 * @property integer $encuesta_id
 * @property integer $asignatura_id
 * @property integer $docente_id
 * @property string $identificador
 * @property string $activo
 * @property string $password
 * @property Model_Encuesta $Encuesta
 * @property Model_PeriodoLectivo $PeriodoLectivo
 * @property Model_Asignatura $Asignatura
 * @property Model_Docente $Docente
 * @property Doctrine_Collection $Respuesta
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_EncParticular extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('enc_particular');
        $this->hasColumn('enc_particular_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'enc_particular_enc_particular_id',
             ));
        $this->hasColumn('periodo_lectivo_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('encuesta_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('asignatura_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('docente_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('identificador', 'string', null, array(
             'type' => 'string',
             'fixed' => true,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('activo', 'string', null, array(
             'type' => 'string',
             'fixed' => true,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
		$this->hasColumn('password', 'string', null, array(
			'type' => 'string',
			'length' => 30,
			'fixed' => true,
			'notnull' => false,
			'primary' => false,
		));
		$this->hasColumn('fecha_inicio', 'datetime', null, array(
			'type' => 'datetime',
			'fixed' => true,
			'notnull' => false,
			'primary' => false,
		));
		$this->hasColumn('fecha_fin', 'datetime', null, array(
			'type' => 'datetime',
			'fixed' => true,
			'notnull' => false,
			'primary' => false,
		));
		$this->hasColumn('max_resp', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'fixed' => false,
			'unsigned' => false,
			'notnull' => false,
			'primary' => false,
		));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_Encuesta as Encuesta', array(
             'local' => 'encuesta_id',
             'foreign' => 'encuesta_id'));

        $this->hasOne('Model_PeriodoLectivo as PeriodoLectivo', array(
             'local' => 'periodo_lectivo_id',
             'foreign' => 'periodo_lectivo_id'));

        $this->hasOne('Model_Asignatura as Asignatura', array(
             'local' => 'asignatura_id',
             'foreign' => 'asignatura_id'));

        $this->hasOne('Model_Docente as Docente', array(
             'local' => 'docente_id',
             'foreign' => 'docente_id'));

        $this->hasMany('Model_Respuesta as Respuesta', array(
             'local' => 'enc_particular_id',
             'foreign' => 'enc_particular_id'));
    }
}