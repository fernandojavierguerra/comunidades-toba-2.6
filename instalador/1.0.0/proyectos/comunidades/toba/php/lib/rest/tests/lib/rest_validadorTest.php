<?php

namespace rest\tests\lib;

use rest\lib\rest_error;
use rest\lib\rest_validador;

class rest_validadorTest extends \PHPUnit_Framework_TestCase
{


	public function testOK(){
		$this->assertEquals(true, true);
	}


	public function testVacio()
	{
		$regla = array(
			'campo' => array(rest_validador::TIPO_LONGITUD => array('min' => 1, 'max' => 2))
		);
		$dato = array('campo' => '');
		rest_validador::validar($dato, $regla);
		$this->assertTrue(true);
	}

	/**
	 */
	public function testLongitudOk()
	{
		$regla = array(
			'campo' => array(rest_validador::TIPO_LONGITUD => array('min' => 1, 'max' => 2))
		);
		$dato = array('campo' => '12');
		rest_validador::validar($dato, $regla);
		$this->assertTrue(true);
	}

	/**
	 * @expectedException rest\lib\rest_error
	 */
	public function testLongitudError()
	{
		$regla = array(
			'campo' => array(rest_validador::TIPO_LONGITUD => array('min' => 1, 'max' => 2))
		);
		$dato = array('campo' => '123');
		rest_validador::validar($dato, $regla);
	}
	/**
	 * @expectedException rest\lib\rest_error
	 */
	public function testLongitudError2()
	{
		$regla = array(
			'campo' => array(rest_validador::TIPO_LONGITUD => array('min' => 2))
		);
		$dato = array('campo' => '1');
		rest_validador::validar($dato, $regla);
	}

	public function testOKs(){
		$regla = array(
			'int'     => array(rest_validador::TIPO_INT => array('min' => 2, 'max' => 50)),
			'numer'   => array(rest_validador::TIPO_NUMERIC => array('min' => 8.34)),
			'alfa'    => array(rest_validador::TIPO_ALPHA),
			'alfanum' => array(rest_validador::TIPO_ALPHANUM),
			'date'    => array(rest_validador::TIPO_DATE => array('format' => 'd/m/Y')),
			'time'    => array(rest_validador::TIPO_TIME => array('format' => 'H:i:s')),
			'enum'    => array(rest_validador::TIPO_ENUM => array('A', 'B', 'C')),
			'texto'   => array(rest_validador::TIPO_TEXTO),
			'long'    => array(rest_validador::TIPO_LONGITUD => array('min' => 2)),
			'oblig'    => array(rest_validador::OBLIGATORIO)
		);

		$datos = array(
			'int'     => 50,
			'numer'   => 8.35,
			'alfa'    => 'abcdXYZ',
			'alfanum' => 'abcdXYZ1234567890',
			'date'    => '04/10/1999',
			'time'    => '15:30:05',
			'enum'    => 'C',
			'texto'   =>  '234j23io-+`+/*',
			'long'    => '////////',
			'oblig' => 'fasd'
		);
		rest_validador::validar($datos, $regla);
	}

	public function testErrores(){
		$regla = array(
			'int'     => array(rest_validador::TIPO_INT => array('min' => 2, 'max' => 50)),
			'numer'   => array(rest_validador::TIPO_NUMERIC => array('min' => 8.34)),
			'alfa'    => array(rest_validador::TIPO_ALPHA),
			'alfanum' => array(rest_validador::TIPO_ALPHANUM),
			'date'    => array(rest_validador::TIPO_DATE => array('format' => 'd/m/Y')),
			'time'    => array(rest_validador::TIPO_TIME => array('format' => 'H:i:s')),
			'enum'    => array(rest_validador::TIPO_ENUM => array('A', 'B', 'C')),
			'long'    => array(rest_validador::TIPO_LONGITUD => array('min' => 2)),
			'oblig'    => array(rest_validador::OBLIGATORIO)
		);

		$datos = array(
			'int'     => 'a',
			'numer'   => 8.25,
			'alfa'    => '123abcd',
			'alfanum' => '--abcdXYZ1234567890',
			'date'    => '30/30/1999',
			'time'    => '15-30:05',
			'enum'    => 'D',
			'long'    => '/'
		);
		try{
			rest_validador::validar($datos, $regla);
		}catch (rest_error $e){
			//fallaron todas las reglas
			$this->assertEquals(count($regla), count($e->get_datalle()));
			return;
		}
		$this->assertTrue(false, "No se lanzo la excepci�n por los errores");
	}
}
 