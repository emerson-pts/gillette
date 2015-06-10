<?php
/**
* RayArray arrays utility class
*
* This class provides configuration array handling funcionalities,
* may be usefull when dealing with configuration data.
*
* Usage: using this class you can convert a multidimensional configuration
* array into a single dimension array ready to store into sql table/ flat file.
*
* methods available are
*  - shorten() - static
*  - unshorten() - static
*  - subarray() - static
*
* @package     raynux
* @subpackage  raynux.lab.array
* @version     1.0
* @author      Md. Rayhan Chowdhury
* @email       ray@raynux.com
* @website     www.raynux.com
* @license     GPL
*/
class ArrayDimensionBehavior extends ModelBehavior {
	/**
	 * Shorten an multidimensional array into a single dimensional array concatenating all keys with separator.
	 *
	 * @example array('country' => array(0 => array('name' => 'Bangladesh', 'capital' => 'Dhaka')))
	 *          to array('country.0.name' => 'Bangladesh', 'country.0.capital' => 'Dhaka')
	 *
	 * @param array $inputArray, arrays to be marged into a single dimensional array
	 * @param string $path, Default Initial path
	 * @param string $separator, array key path separator
	 * @return array, single dimensional array with key and value pair
	 * @access public
	 * @static
	 
	 
	 deep vai fazer com que a quantidade de níveis indicada seja respeitada
	 */
	static public function shorten($model, array $inputArray, $deep = 1, $path = null, $separator = "."){
	   $data = array();
	   
	   if (!is_null($path) && $path !== true) {
		  $path = $path . $separator;
	   }else if($path == true){
			$path = '';
	   }

	   if (is_array($inputArray)) {
		  foreach ($inputArray as $key => &$value) {
			 if (!is_array($value)) {
				$data[$path . $key] = $value;
			 } else if ($deep == null){
				$data = array_merge($data, self::shorten($model, $value, $deep, (is_null($path) ? $path : $path . $key), $separator));
			 } else{
				$data[$path . $key] = self::shorten($model, $value, $deep-1, (is_null($path) ? $path : $path . $key), $separator);
			 }
		  }
	   }

	   return $data;
	}

	/**
	 * Unshorten a single dimensional array into multidimensional array.
	 *
	 * @example array('country.0.name' => 'Bangladesh', 'country.0.capital' => 'Dhaka')
	 *          to array('country' => array(0 => array('name' => 'Bangladesh', 'capital' => 'Dhaka')))
	 *
	 * @param array $data data to be converted into multidimensional array
	 * @param string $separator key path separator
	 * @return array multi dimensional array
	 * @access public
	 * @static
	 */
	static public function unshorten($model, $data, $separator = '.'){
	   $result = array();

	   foreach ($data as $key => $value){
		  if(strpos($key, $separator) !== false ){
			 $str = explode($separator, $key, 2);
			 $result[$str[0]][$str[1]] = $value;
			 if(strpos($str[1], $separator)){
				$result[$str[0]] = self::unshorten($result[$str[0]], $separator);
			 }
		  }else{
			 $result[$key] = is_array($value)?  self::unshorten($value, $separator) : $value;
		  }
	   }
	   return $result;
	}

	/**
	 * Get part of array from a multidimensional array specified by concatenated keys path.
	 *
	 * @example
	 *          path = "0.name"
	 *          data =
	 *          array(
	 *                  array('name' => array('Bangladesh', 'Srilanka', 'India', 'Pakistan')),
	 *                  array('help' => 'help.php'),
	 *                  'test' => 'value',
	 *                  10 =>
	 *          false)
	 *          will return array('Bangladesh', 'Srilanka', 'India', 'Pakistan')
	 * @param string $path
	 * @param array $data
	 * @param string $separator path separator default '.'
	 * @return mixed and return NULL if not found.
	 * @access public
	 * @static
	 */
	static public function subarray($model, $path, &$data, $separator = '.') {
	   if (strpos($path, $separator) === false) {
		  if (isset($data[$path])) {
			 return $data[$path];
		  }
	   } else {
		  $keys = explode($separator, $path, 2);
		  if (array_key_exists($keys[0], $data)) {
			 return self::subarray($keys[1], $data[$keys[0]], $separator);
		  }
	   }
	}
}
