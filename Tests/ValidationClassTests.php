<?php
require_once ("../simpletest/autorun.php");

/**
 *
 * Class for testing the methods validation class
 * 
 * @author Luke
 *        
 */
class ValidationClassTests extends UnitTestCase {
	private $validation;
	public function setUp() {
		require_once ("../app/models/Validation.php");
		$this->validation = new Validation ();
	}
	public function testIsEmailValid() {
		$this->assertTrue ( $this->validation->isEmailValid ( "luca.longo@dit.ie" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "luca.@.com" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( ".com" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "luca" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "@" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "123" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( null ));
		$this->assertFalse ( $this->validation->isEmailValid ( array()));
		$this->assertFalse ( $this->validation->isEmailValid ( -1));
		$this->assertFalse ( $this->validation->isEmailValid ( 9999));
		$this->assertFalse ( $this->validation->isEmailValid ( "-1"));
	}
	public function testIsNumberInRangeValid() {
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( 5, 4, 6 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( "5", 4, 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( "ww", 4, 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, 7, 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, 4, 3 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, "4a", 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, 4, "ff" ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( "a", "b", -5 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( "ads", null, 50 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( null, array(), "-50" ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( -5, -10, -7 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( -7, -10, -5 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( -7, -5, -10 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( -7, -10, -5 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( -7, -7, -7 ) );
		
	}
	public function testIsLengthStringValid() {
		$this->assertFalse( $this->validation->isLengthStringValid ( "luca", "5" ) );
		$this->assertTrue ( $this->validation->isLengthStringValid ( "luca", 6 ) );
		$this->assertTrue ( $this->validation->isLengthStringValid ( "luca", 4 ) );
		$this->assertFalse ( $this->validation->isLengthStringValid ( "luca", 4.6 ) );
		$this->assertFalse ( $this->validation->isLengthStringValid ( 1, 5 ) );
		$this->assertFalse ( $this->validation->isLengthStringValid ( "luca", "a" ) );
	}
	
	public function testIsYearValid() {
		$this->assertTrue( $this->validation->isYearValid ( 2006 ) );
		$this->assertTrue( $this->validation->isYearValid ( 1985 ) );
		$this->assertTrue( $this->validation->isYearValid ( "2006" ) );
		$this->assertFalse( $this->validation->isYearValid ( "hello" ) );
		$this->assertFalse( $this->validation->isYearValid ( 1800 ) );
		$this->assertFalse( $this->validation->isYearValid ( 2020 ) );
	}
	
	public function testisLengthTimeValid() {
		$this->assertTrue( $this->validation->isLengthTimeValid("00:21"));
		$this->assertTrue( $this->validation->isLengthTimeValid("59:59"));
		$this->assertTrue( $this->validation->isLengthTimeValid("03:20"));
		$this->assertFalse( $this->validation->isLengthTimeValid("61:00"));
		$this->assertFalse( $this->validation->isLengthTimeValid("34:67"));
		$this->assertFalse( $this->validation->isLengthTimeValid("161:00"));
		$this->assertFalse( $this->validation->isLengthTimeValid("31:345"));
		$this->assertFalse( $this->validation->isLengthTimeValid("2:32"));
		$this->assertFalse( $this->validation->isLengthTimeValid("02:3"));
		$this->assertFalse( $this->validation->isLengthTimeValid("2:3"));
		$this->assertFalse( $this->validation->isLengthTimeValid("2:"));
		$this->assertFalse( $this->validation->isLengthTimeValid(":3"));
		$this->assertFalse( $this->validation->isLengthTimeValid(":30"));
		$this->assertFalse( $this->validation->isLengthTimeValid(":"));
		$this->assertFalse( $this->validation->isLengthTimeValid("test"));
		$this->assertFalse( $this->validation->isLengthTimeValid(1234));
	}
}
?>