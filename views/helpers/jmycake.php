<?php 
class JmycakeHelper extends AppHelper { 
    var $helpers = array('Javascript'); 
     
    /* 
     * $idInput = ID dell'input text su cui fare l'autocomplete 
     * $modelSearch = Modello/NomeCampo nel quale cercare al stringa inserita nell'input 
     * Use {NomeCampo|NomeCampo2} se quiser que a busca seja realizada em mais de 1 campo
     * $other = Array che contiene l'id del campo da aggiornare ed il nome del campo da prendere dal db 
     * $numResult = Numero di risultati da mostrare nella lista 
     * $strlen = Numero di caratteri dopo i quali iniziare le richieste dell'autocomplete 
     */ 
    function autocomplete($idInput,$modelSearch,$other=null,$numResult=7,$strlen=3) { 
        $fields= ""; 
        $setBody = "";
        
        //If modelSearch begin with /, then use the first piece as controller url
        if(preg_match('/^\/(.*)\/[^\/]+\/[^\/]+$/', $modelSearch, $searchController)){
			$modelSearch = preg_replace('/^\/(.*)\/([^\/]+\/[^\/]+)$/', '\2', $modelSearch);
		}

        $search = explode("/",$modelSearch); 
        if (is_array($other)) {  
            foreach ($other As $k => $v) { 
                $fields .= $v.','; 
                $setBody .= "if($('#".$k."').attr('value') == undefined)
					$('#".$k."').html(".$v.");
				else
					$('#".$k."').val(".$v.");"; 
            } 
        }
        
        $search_preg_clean = '/^\{?[^\.]*\.(.*?)(?:\|.*|$)/';
        
        $fields .= preg_replace($search_preg_clean, '\1', $search[1]); 
        
        return $this->Javascript->codeBlock('
			var autocomplete'.$idInput.'TimeoutId = null;
 
            $("#'.$idInput.'").ready(function(){                 
                $("#'.$idInput.'")
					.keydown(function(event){
						pressedKey = event.charCode || event.keyCode || -1; 
						if(pressedKey != 13 && pressedKey != 38  && pressedKey != 40){
							clearTimeout(autocomplete'.$idInput.'TimeoutId);
							autocomplete'.$idInput.'TimeoutId = setTimeout(query_'.$idInput.', 500);
						}else if(pressedKey == 38  || pressedKey == 40){
							dimensione=$("#span_'.$idInput.'>ul>li").size(); 
							if(dimensione == 0)return;
							switch(pressedKey) { 
								case 38://up 
									$(this).attr("position", parseInt($(this).attr("position"))-1);
									if(parseInt($(this).attr("position")) < 0)$(this).attr("position", dimensione-1); 
									break; 
							 
								case 40://down 
									$(this).attr("position", parseInt($(this).attr("position"))+1);
									if(parseInt($(this).attr("position")) > dimensione-1)$(this).attr("position", 0); 
								break; 
							} 
							$("#span_'.$idInput.'>ul>li>a").removeClass("active").eq($(this).attr("position")).addClass("active"); 
						}else if(pressedKey == 13){
							//Return
							$("#span_'.$idInput.'>ul>li>a:eq(" + $(this).attr("position") + ")").click();
							return;
						}
					})
					.attr("autocomplete","off")
					.after("<span id=\"span_'.$idInput.'\" class=\"autocomplete_live\"></span>");
            }); 
         
         
            function query_'.$idInput.'() { 
				var theInput = $("#'.$idInput.'");
				var txt=theInput.val(); 
				
                if(txt.length >= '.$strlen.') { 
                    $("#'.$idInput.'Indicator").remove();
					$("#'.$idInput.'").after("<span id=\"'.$idInput.'Indicator\" class=\"indicator\"></span>");
					$.post("'.$this->webroot.(count($searchController) != 0 ? $searchController[1] : $this->params["controller"].'/autocomplete').'", {query: txt, fields: "'.$fields.'", search: "'.$search[1].'", model: "'.$search[0].'", numresult: "'.$numResult.'", rand: "'.$idInput.'"}, function(data){
						$("#'.$idInput.'").attr("position", -1);
						$("#'.$idInput.'Indicator").remove();
                        $("#span_'.$idInput.'").html("<ul id=\'ul_'.$idInput.'\' class=\'autocomplete_live\'>"+data+"</ul>");
                        
                        var inputPosition = theInput.position();
                        $("#ul_'.$idInput.'")
							.width(theInput.innerWidth())
							.css({left: inputPosition.left, top: inputPosition.top + theInput.innerHeight() + parseInt(theInput.css("borderTopWidth"), 10) + parseInt(theInput.css("borderBottomWidth"), 10)});
						
						$("#span_'.$idInput.'>ul>li>a")
							.mouseover(function(){
								$("#span_'.$idInput.'>ul>li:eq("+ $("#'.$idInput.'").attr("position") + ")>a").removeClass("active");
								$(this).addClass("active");
								$("#'.$idInput.'").attr("position", $(this).parent().prevAll().length);
							})
						;
                    });     
                }             
            } 
                         
            function set_'.$idInput.'('.$fields.') { 
                '.$setBody.' 
                $("#'.$idInput.'").val('.preg_replace($search_preg_clean, '\1', $search[1]).'); 
                $("#span_'.$idInput.'").html("");
                if(typeof '.$idInput.'_onupdate == "function")'.$idInput.'_onupdate();
            } 
        '); 
    } 
} 