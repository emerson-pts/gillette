<?php
class FormatacaoHelper extends Helper {
	var $helpers = array('Time', 'Number', 'Text', 'Html',);

	var $diasDaSemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
	var $meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

	function pagebreak($text, $params = array()){
		$default_params = array(
			'title' 	=> 'Páginas: ',
			'url'		=> 'url.url',
			'page_key'	=> 'pagina',
			'break' 	=> array('pagebreak', 'quebra-pagina'),
		);
		
		$params += $default_params;

		$params['url_extract'] = Set::extract($params['url'], $this->params);
		if(empty($params['url_extract']))debug('Erro na definição da url '.$params['url'].' - retornou '.print_R($params['url_extract'], true));
		
		if(!is_array($params['break']))$params['break'] = array($params['break']);
		
		//Divite conteúdo em páginas
		$split = preg_split('/\[('.implode('|', $params['break']).')(?:\:(	[^\]+]))?\]/i', $text);
		
		//se tem apenas 1 página, retorna conteúdo
		if(count($split) == '1')return $split[0];
		
		//Verifica página atual
		if(!isset($this->params['named'][$params['page_key']]) || !is_numeric($this->params['named'][$params['page_key']]) || $this->params['named'][$params['page_key']] > count($split)){
			$pagina = 1;
		}else{
			$pagina = $this->params['named'][$params['page_key']];
		}
		
		$current_page = $split[$pagina-1];
		while(preg_match('/^<\/([^>]+)>*/s', $current_page))$current_page = trim(preg_replace('/^<\/([^>]+)>*/s', '', $current_page));
		$current_page = trim(preg_replace('/(^\s*<br *\/?>\s*|\s*<br *\/?>\s*$)/s', '', $current_page));

		if(class_exists('tidy')){
			$tidy = new tidy();
			$tidy->parseString($current_page,array('show-body-only'=>true, 'wrap' => false),'utf8');
			$tidy->cleanRepair();
			$current_page = $tidy;
		}
//		$current_page .= $this->autoCloseTags($current_page);
		
		$current_page .= '<br class="clear" /><div class="paginate"><span class="paginate-title">'.$params['title'].'</span>';
		for($i = 1;$i<=count($split);$i++){
			$current_page .= $this->Html->link(
				$i, 
				'/'.preg_replace('/\/'.preg_quote($params['page_key'],'/').':[0-9]+(\/|$)/', '', $params['url_extract']).'/'.$params['page_key'].':'.$i,
				array('class' => ($pagina == $i ? 'active' : ''),)
			);
		}
		$current_page .= '</div>';
		//echo '--------'.$current_page;exit;
		return $current_page;
	}

	function diff($old, $new){
		$old = ($old == '' ? array() : str_split($old));
		$new = ($new == '' ? array() : str_split($new));
		
		$old_count = count($old);
		$new_count = count($new);
		
		$count = ($old_count > $new_count ? $old_count: $new_count);
		$string = '';
		$old_i = $new_i = 0;
		$open_insert = $open_delete = false;
		for($i = 0; $i < $count; $i++){
			//São iguais
			if(isset($new[$new_i]) && isset($old[$old_i]) && strcmp($new[$new_i], $old[$old_i]) == 0){
				$string .= ($open_insert ? '</span>' : '').($open_delete ? '</span>' : '').$new[$new_i];
				$open_insert = $open_delete = false;
			}
			//Checa se o velho não chegou ao fim e próxima letra do velho é igual ao novo, então deletou a atual do velho
			else if(isset($new[$new_i]) && isset($old[$old_i+1]) && strcmp($new[$new_i], $old[$old_i+1]) == 0){
				$string .= ($open_insert ? '</span>' : '').($open_delete ? '' : '<span class="delete">').$old[$old_i].'</span>'.$new[$new_i];
				$open_insert = $open_delete = false;
				$old_i++;
			}
			//Se o velho não chegou ao fim e a próxima letra do novo é diferente do velho, então inseriu
			else if(isset($new[$new_i+1]) && isset($old[$old_i]) && isset($old[$old_i+1]) && strcmp($new[$new_i+1], $old[$old_i]) == 0){
				$string .= ($open_delete ? '</span>' : '').($open_insert ? '' : '<span class="insert">').$new[$new_i].'</span>'.$old[$old_i];
				$open_insert = $open_delete = false;
				$new_i++;
				$i++;
			}
			//Se chegou ao fim do novo, mas não acabou o velho, então deletou
			else if(isset($old[$old_i]) && !isset($new[$new_i])){
				$string .= ($open_insert ? '</span>' : '').($open_delete ? '' : '<span class="delete">').$old[$old_i];
				$open_delete = true;
				$open_insert = false;
			}
			//Se chegou ao fim do novo, mas não acabou o velho, então incluiu
			else if(!isset($old[$old_i]) && isset($new[$new_i])){
				$string .= ($open_delete ? '</span>' : '').($open_insert ? '' : '<span class="insert">').$new[$new_i];
				$open_delete = false;
				$open_insert = true;
			}
			//Então apagou o atual e inseriu um novo
			else if(isset($old[$old_i]) && isset($new[$new_i])){
				$string .= ($open_insert ? '</span>' : '').($open_delete ? '' : '<span class="delete">').$old[$old_i].'</span><span class="insert">'.$new[$new_i];
				$open_delete = false;
				$open_insert = true;
			}else if(!isset($old[$old_i]) && isset($new[$new_i])){
				$string .= ($open_delete ? '</span>' : '').($open_insert ? '' : '<span class="insert">').$new[$new_i];
				$open_delete = false;
				$open_insert = true;
			}
			
			$old_i++;
			$new_i++;
		}
		
		if($open_delete)$string .= '</span>';
		if($open_insert)$string .= '</span>';
		
		//Remove as alternancias entre insert e delete
		$preg_replaces = array(
			'#<span class="(delete)">(.*?)</span><span class="(insert)">(.*?)</span><span class="delete">(.*?)</span>#' => '<span class="\1">\2\5</span><span class="\3">\4</span>',
			'#<span class="(insert)">(.*?)</span><span class="(delete)">(.*?)</span><span class="insert">(.*?)</span>#' => '<span class="\1">\2\5</span><span class="\3">\4</span>',
		);
		
		while(count($preg_replaces) != 0){
			foreach($preg_replaces AS $preg=>$replace){	
				if(!preg_match($preg, $string)){
					unset($preg_replaces[$preg]);
				}else{
					$string = preg_replace($preg, $replace, $string);
				}
			}
		}

		if(preg_match_all('#(<span class="(insert|delete)">(.*?)</span>|((?!<span).))#', $string, $matches)){
			$count_matches = count($matches[0]);
			$new_string = '';
			for($i=0;$i<$count_matches;$i++){
				if(strlen($matches[2][$i]) == 0){
					$new_string .= $matches[4][$i];
				}else{
					$new_string .= '<span class="'.$matches[2][$i].'">'.$matches[3][$i];
					while(isset($matches[0][$i+1]) && $matches[2][$i] == $matches[2][$i+1]){
						$new_string .= $matches[3][$i+1];
						$i++;
					}
					$new_string .= '</span>';
				}
			}
		}	
		return $this->output($new_string);
	}

	/* Datas */
	function data($data = null, $opcoes = array()) {
		$padrao = array(
			'invalid' => '31/12/1969',
			'userOffset' => null
		);
		$config = array_merge($padrao, $opcoes);
    
		$data = $this->_ajustaDataHora($data);
		return $this->Time->format('d/m/Y', $data, $config['invalid'], $config['userOffset']);
	}

	function dataHora($dataHora = null, $segundos = true) {
		$dataHora = $this->_ajustaDataHora($dataHora);
		if ($segundos) {
			return $this->Time->format('d/m/Y H:i:s', $dataHora);
		}
		return $this->Time->format('d/m/Y H:i', $dataHora);
	}

	function dataCompleta($dataHora = null, $formato = '%s, %02d de %s de %04d, %s') {

		$dataHora = $this->_ajustaDataHora($dataHora);
		$w = date('w', $dataHora);
		$n = date('n', $dataHora) - 1;

		return sprintf($formato, $this->diasDaSemana[$w], date('d', $dataHora), $this->meses[$n], date('Y', $dataHora), date('H:i:s', $dataHora));
	}

	function mesPorExtenso($mes = null) {
		if(is_null($mes))$mes = date('m');
		$mes--;
		return $this->meses[$mes];
	}

	function _ajustaDataHora($data) {
		if (is_null($data)) {
			return time();
		}
		return $data;
	}

	/* Números */

	function precisao($numero, $casasDecimais = 3) {
		return sprintf("%01.{$casasDecimais}f", $numero);
	}

	function porcentagem($numero, $casasDecimais = 2) {
		return $this->precisao($numero, $casasDecimais) . '%';
	}

	function moeda($valor, $opcoes = array()) {
		$padrao = array(
			'before'=> 'R$ ',
			'after' => '',
			'zero' => 'R$ 0,00',
			'places' => 2,
			'thousands' => '.',
			'decimals' => ',',
			'negative' => '<span class="negative">(%s)</span>',
			'escape' => true
		);
		$config = array_merge($padrao, $opcoes);
		if ($valor > -1 && $valor < 1) {
			$formatado = $this->Number->format(abs($valor), $config);
			if ($valor < 0 ) {
				$formatado = sprintf($config['negative'], $formatado);
/*				if ($config['negative'] == '()') {
					$formatado = '(' . $formatado .')';
				} else {
					$formatado = $config['negative'] . $formatado;
				}
*/
			}
			return $formatado;
		}
		$formatado = $this->Number->currency($valor, null, array('negative' => '') + $config);
		if($valor < 0)$formatado = sprintf($config['negative'], $formatado);
		
		return $formatado;
	}

	function moedaPorExtenso($numero) {
		// Adaptado de http://forum.imasters.uol.com.br/index.php?showtopic=125375
		$singular = array('centavo', 'Real', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão');
		$plural = array('centavos', 'Reais', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões');

		$c = array('', 'cem', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos');
		$d = array('', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa');
		$d10 = array('dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezesete', 'dezoito', 'dezenove');
		$u = array('', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove');

		$z = 0;
		$rt = '';

		$valor = number_format($numero, 2, '.', '.');
		$inteiro = explode('.', $valor);
		$tamInteiro = count($inteiro);

		// Normalizandos os valores para ficarem com 3 digitos
		$inteiro[0] = sprintf('%03d', $inteiro[0]);
		$inteiro[$tamInteiro - 1] = sprintf('%03d', $inteiro[$tamInteiro - 1]);

		$fim = $tamInteiro - 1;
		if ($inteiro[$tamInteiro - 1] <= 0) {
			$fim--;
		}
		foreach ($inteiro as $i => $valor) {
			$rc = $c[$valor{0}];
			if ($valor > 100 && $valor < 200) {
				$rc = 'cento';
			}
			$rd = '';
			if ($valor{1} > 1) {
				$rd = $d[$valor{1}];
			}
			$ru = '';
			if ($valor > 0) {
				if ($valor{1} == 1) {
					$ru = $d10[$valor{2}];
				} else {
					$ru = $u[$valor{2}];
				}
			}

			$r = $rc;
			if ($rc && ($rd || $ru)) {
				$r .= ' e ';
			}
			$r .= $rd;
			if ($rd && $ru) {
				$r .= ' e ';
			}
			$r .= $ru;
			$t = $tamInteiro - 1 - $i;
			if (!empty($r)) {
				$r .= ' ';
				if ($valor > 1) {
					$r .= $plural[$t];
				} else {
					$r .= $singular[$t];
				}
			}
			if ($valor == '000') {
				$z++;
			} elseif ($z > 0) {
				$z--;
			}
			if ($t == 1 && $z > 0 && $inteiro[0] > 0) {
				if ($z > 1) {
					$r .= ' de ';
				}
				$r .= $plural[$t];
			}
			if (!empty($r)) {
				if ($i > 0 && $i < $fim  && $inteiro[0] > 0 && $z < 1) {
					if ($i < $fim) {
						$rt .= ', ';
					} else {
						$rt .= ' e ';
					}
				} elseif ($t == 0 && $inteiro[0] > 0) {
					$rt .= ' e ';
				} elseif ($rt != '' && $valor{1} == 0 && $inteiro[1] > 0) {
					$rt .= ' e ';
				} else {
					$rt .= ' ';
				}
				$rt .= $r;
			}
		}

		if (empty($rt)) {
			return 'zero';
		}
		return trim($rt);
	}

	
	function plural($s, $c, $showNumber = true) {
		if ($c != 1) {
			return $c . ' ' . Inflector::pluralize($s);
		}
		return ($showNumber == true ? $c . ' ' : '') . $s;
	}
	
	function cnpj($cnpj){
		return preg_replace('/(^| |\()([0-9]{1,3})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})( |$|\))/', '\1\2.\3.\4/\5-\6\7', $cnpj);
	}
	
	function cpf($cpf){
		return preg_replace('/(^| |\()([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})( |$|\))/', '\1\2.\3.\4-\5\6', $cpf);
	}
	
	function cnpj_cpf($cnpj_cpf){
		return self::cnpj(self::cpf($cnpj_cpf));
	}

	function telefoneToList($telefones){
		array_walk($telefones,create_function('&$value, $index', '$value = $value[\'telefone_formatado\'];'));
		return $this->Text->toList($telefones, 'e');
	}
	
	function cep($cep){
		return preg_replace('/^([0-9]{5})([0-9]{3})$/','\1-\2', $cep);
	}

	function maiuscula($str){
		$str = strtoupper($str);
		$replaces = array(
			'/á/' => 'Á',
			'/à/' => 'À',
			'/â/' => 'Â',
			'/ã/' => 'Ã',
			'/ä/' => 'Ä',
			'/é/' => 'É',
			'/è/' => 'È',
			'/ê/' => 'Ê',
			'/ë/' => 'Ë',
			'/í/' => 'Í',
			'/ì/' => 'Ì',
			'/î/' => 'Î',
			'/ï/' => 'Ï',
			'/ó/' => 'Ó',
			'/ò/' => 'Ò',
			'/ô/' => 'Ô',
			'/õ/' => 'Õ',
			'/ö/' => 'Ö',
			'/ú/' => 'Ú',
			'/ù/' => 'Ù',
			'/û/' => 'Û',
			'/ü/' => 'Ü',
			'/ç/' => 'Ç',
		);
		return preg_replace(array_keys($replaces), $replaces, $str);
	}

	function autoCloseTags($string) {
		// automatically close HTML-Tags
		// (usefull e.g. if you want to extract part of a blog entry or news as preview/teaser)
		// coded by Constantin Gross <connum at googlemail dot com> / 3rd of June, 2006
		// feel free to leave comments or to improve this function!

		$donotclose=array('br','img','input'); //Tags that are not to be closed

		//prepare vars and arrays
		$tagstoclose='';
		$tags=array();

		//put all opened tags into an array
		preg_match_all("/<(([A-Z]|[a-z]).*)(( )|(>))/isU",$string,$result);
		$openedtags=$result[1];
		$openedtags=array_reverse($openedtags); //this is just done so that the order of the closed tags in the end will be better

		//put all closed tags into an array
		preg_match_all("/<\/(([A-Z]|[a-z]).*)(( )|(>))/isU",$string,$result2);
		$closedtags=$result2[1];

		//look up which tags still have to be closed and put them in an array
		for ($i=0;$i<count($openedtags);$i++) {
		   if (in_array($openedtags[$i],$closedtags)) { unset($closedtags[array_search($openedtags[$i],$closedtags)]); }
			   else array_push($tags, $openedtags[$i]);
		}

		$tags=array_reverse($tags); //now this reversion is done again for a better order of close-tags

		//prepare the close-tags for output
		for($x=0;$x<count($tags);$x++) {
		$add=strtolower(trim($tags[$x]));
		if(!in_array($add,$donotclose)) $tagstoclose.='</'.$add.'>';
		}
		//and finally
		return $tagstoclose;
	}

}
