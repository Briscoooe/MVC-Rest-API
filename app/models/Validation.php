<?php
class Validation {
	/**
	 * check whether the email string is a valid email address using a regular expression
	 * @param $emailStr - the input email string
	 * @return boolean indicating whether it is a valid email or not
	 */
	public function isEmailValid($emailStr){
		if(is_string($emailStr))
		{
			$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i";
			if(!preg_match($regex, $emailStr)) return (false);
			else return (true);
		}
		return (false);
	}
	/**
	 * @param $number - the input number
	 * @param $min - the minimum value for the input number
	 * @param $max - the maximum value for the input number
	 * @return boolean indicating whether it is a valid number in the input range
	 */
	public function isNumberInRangeValid ($number, $min, $max){
		if (is_numeric($number) && is_numeric($min) && is_numeric($max))
			if ($number>= $min && $number<= $max) return (true);
		return (false);
	}
	/**
	 * @param $string - the input string
	 * @param $maxchars - the maximum length of the input string
	 * @return boolean indicating whether it is a valid string of the right max length
	 */
	public function isLengthStringValid($string, $maxchars){
		if (is_string($string) && is_int($maxchars))
			if (strlen($string)<=$maxchars) return (true);	
		return (false);
	}
	
	/**
	 * check whether the date passed is valid
	 * @param $year - the year input integer
	 * @return boolean indicating whether it is a valid date or not
	 */
	public function isYearValid($year) {
		if(is_numeric($year) && $year > 1901 && $year <= date("Y"))
			return true;
		return false;
	}
	
	/**
	 * check whether the length of time passed is valid
	 * @param $length - the song length string
	 * @return boolean indicating whether it is a valid length or not
	 */
	public function isLengthTimeValid($length) {
		if(is_string($length))
			if(preg_match("/^([0-5][0-9]):([0-5][0-9])$/", $length))
				return true;
		return false;
	}
}
?>