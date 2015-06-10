<?php
/**
 * Get Enum Values
 * Snippet v0.1.3
 * http://cakeforge.org/snippet/detail.php?type=snippet&id=112
 *
 * Gets the enum values for MySQL 4 and 5 to use in selectTag()
 */
class EnumSetBehavior extends ModelBehavior {
	function setup($model) {
        $this->model = $model;
	}
	
	function getEnumValues($model, $columnName=null, $respectDefault=false){
		$cacheKey = $model->alias . '_' . $columnName . '_enum_options';
		if ($assoc_values = Cache::read($cacheKey)) {
			return $assoc_values;
		}

   		if ($columnName==null) { return array(); } //no field specified

		//Get the values for the specified column (database and version specific, needs testing)
		$result = $model->query("SHOW COLUMNS FROM `{$model->tablePrefix}{$model->useTable}` LIKE '{$columnName}'");

		//figure out where in the result our Types are (this varies between mysql versions)
		$types = null;
		if     ( isset( $result[0]['COLUMNS']['Type'] ) ) { $types = $result[0]['COLUMNS']['Type']; $default = $result[0]['COLUMNS']['Default']; } //MySQL 5
		elseif ( isset( $result[0][0]['Type'] ) )         { $types = $result[0][0]['Type']; $default = $result[0][0]['Default']; } //MySQL 4
		else   { return array(); } //types return not accounted for

		//Get the values
		$values = explode("','", preg_replace("/(enum)\('(.+?)'\)/","\\2", $types) );

		if($respectDefault){
				$assoc_values = array("$default"=>Inflector::humanize($default));
				foreach ( $values as $value ) {
						if($value==$default){ continue; }
						$assoc_values[$value] = Inflector::humanize($value);
				}
		}
		else{
				$assoc_values = array();
				foreach ( $values as $value ) {
						$assoc_values[$value] = Inflector::humanize($value);
				}
		}

		Cache::write($cacheKey, $assoc_values);
		return $assoc_values;

	} //end getEnumValues
}