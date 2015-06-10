<?php
class YoutubeHelper extends Helper {
	var $urls = array(
		'videosFromUser' => 'http://gdata.youtube.com/feeds/api/users/%s/uploads?start-index=%d&max-results=%d',
		'userProfile'		 => 'http://gdata.youtube.com/feeds/api/users/%s',
	);
	
	var $itemsPerPage = 30;
		
	function __cache($cacheKey, $data = null){
		//Se enviou os dados, gera cache
		if(!is_null($data))return Cache::write($cacheKey, $data);
		
		//Se tem cache
		if(false !== ($return = Cache::read($cacheKey)))return $return;

		//Inicia o cURL
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);

		return false;
	}
	
	function __cacheKey($method, $args){
		return $method.'_'.md5(serialize($args));
	}
	
	function __log($method, $msg){
		$this->log('['.get_class().'->'.$method.']'.$msg);
		return true;
	}
	
	function getUserProfile($username){
		if(empty($username)){
			$this->__log(__FUNCTION__, 'Nome de usuário não informado!');
			return false;
		}

		//Seta chave do cache
		$args = func_get_args();
		$cacheKey = $this->__cacheKey(__FUNCTION__, $args);
		
		//Se tem cache
		if(false !== ($return = $this->__cache($cacheKey)))return $return;
		
		$url = sprintf($this->urls['userProfile'], $username);

		curl_setopt($this->ch, CURLOPT_URL, $url);
		
		if(!$xmlData = curl_exec($this->ch)){
			$this->__log(__FUNCTION__, 'Falha ao acessar a url '.$url.'!');
			return false;
		}

		App::import('Core', 'Xml');
		$xml = new Xml($xmlData);
		$xmlArray = $xml->toArray();

		$return = array(
			'description'	=> current(Set::extract('/Entry/description', $xmlArray)),
			'firstName' 	=> current(Set::extract('/Entry/firstName', $xmlArray)),
			'xml'			=> $xmlArray,
		);

		$this->__cache($cacheKey, $return);
		
		return $return;
	}
	
	function getVideosFromUser($username, $start = 1){
		if(empty($username)){
			$this->__log(__FUNCTION__, 'Nome de usuário não informado!');
			return false;
		}
		
		if(!is_numeric($start) || empty($start))$start = 1;

		//Seta chave do cache
		$args = func_get_args();
		$cacheKey = $this->__cacheKey(__FUNCTION__, $args);
		
		//Se tem cache
		if(false !== ($return = $this->__cache($cacheKey)))return $return;
		
		$url = sprintf($this->urls['videosFromUser'], $username, $start, $this->itemsPerPage);

		curl_setopt($this->ch, CURLOPT_URL, $url);
		
		if(!$xmlData = curl_exec($this->ch)){
			$this->__log(__FUNCTION__, 'Falha ao acessar a url '.$url.'!');
			return false;
		}

		App::import('Core', 'Xml');
		$xml = new Xml($xmlData);
		$xmlArray = $xml->toArray();
		
		$return = array(
			'total' 		=> current(Set::extract('/Feed/totalResults', $xmlArray)),
			'startIndex' 	=> current(Set::extract('/Feed/startIndex', $xmlArray)),
			'itemsPerPage' 	=> current(Set::extract('/Feed/itemsPerPage', $xmlArray)),
			'videos'	 	=> array(),
			'xml'			=> $xmlArray,
		);
		
		foreach(Set::extract('/Feed/Entry', $xmlArray) AS $entry){
			$return['videos'][] = array(
				'title'			=> current(Set::extract('/Entry/Group/title/value', $entry)),
				'description'	=> current(Set::extract('/Entry/Group/description/value', $entry)),
				'category'		=> current(Set::extract('/Entry/Group/category/label', $entry)),
				'keyword'		=> explode(', ', current(Set::extract('/Entry/Group/keywords', $entry))),
				'player_url'	=> current(Set::extract('/Entry/Group/Player/url', $entry)),
				'duration'		=> current(Set::extract('/Entry/Group/Duration/seconds', $entry)),
				'video_url'		=> current(Set::extract('/Content[isDefault=true]/url', Set::extract('/Entry/Group/Content', $entry))),
				'thumbs'		=> current(Set::extract('/Entry/Group/Thumbnail/.', $entry)),
			);
		}
		
		$this->__cache($cacheKey, $return);
		
		return $return;
	}
}