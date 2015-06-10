<?php 
/**
 * By Cleiton Wasen
 * wasenbr at gmail.com
 * Based in http://www.appservnetwork.com/modules.php?name=News&file=article&sid=8
 *  
 */
class XlsHelper extends Helper {
    
    var $helpers = array();
	var $data = '';
	
	function _construct(){
		Configure::write('debug',0);
	}

    
    /**
     * set the header configuration
     * @param $filename the xls file name
     */
    function setHeader($filename)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary ");
    }
    
    /**
     * write the xls begin of file
     */
    function BOF() {
		echo $return = pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
        $this->data .= $return;
        return;
    }
    
    /**
     * write the xls end of file
     */
    function EOF() {
        echo $return = pack("ss", 0x0A, 0x00);
        $this->data .= $return;
        return;
    }
    
    /**
     * write a number
     * @param $Row row to write $Value (first row is 0)
     * @param $Col column to write $Value (first column is 0)
     * @param $Value number value
     */
    function writeNumber($Row, $Col, $Value) {
        echo $return = pack("sssss", 0x203, 14, $Row, $Col, 0x0);
        $this->data .= $return;
        echo $return = pack("d", $Value);
        $this->data .= $return;
        return;
    }
    
    /**
     * write a string label
     * @param $Row row to write $Value (first row is 0)
     * @param $Col column to write $Value (first column is 0)
     * @param $Value string value
     */
    function writeLabel($Row, $Col, $Value) {
        $L = strlen($Value);
        echo $return = pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        $this->data .= $return;
        echo $return = $Value;
        $this->data .= $return;
        return;
    }

} 
