<?php

$this->set('documentData', array(
    'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
));

$this->set('channelData', array(
    'title' => __("TotalRace - Notícias Recentes", true),
    'link' => $this->Html->url('/noticias', true),
    'description' => __("As notícias mais recentes do mundo da F1.", true),
    'language' => 'pt-br',
));


// You should import Sanitize
App::import('Sanitize');

foreach ($noticias as $noticia) {
    if(empty($noticia['Noticia']['conteudo_preview']))$noticia['Noticia']['conteudo_preview'] = $noticia['Noticia']['conteudo'];
    
    // This is the part where we clean the body text for output as the description
    // of the rss item, this needs to have only text to make sure the feed validates
    $bodyText = preg_replace('=\(.*?\)=is', '', $noticia['Noticia']['conteudo_preview']);
    $bodyText = $this->Text->stripLinks($bodyText);
    $bodyText = Sanitize::stripAll($bodyText);
    $bodyText = $this->Text->truncate($bodyText, 400, array(
        'ending' => '...',
        'exact' => true,
        'html' => true,
    ));
    
    echo $this->Rss->item(array(), array(
        'title' => '['.$noticia['Noticia']['tipo_formatado'].'] '.$noticia['Noticia']['titulo'],
        'link' => $noticia['Noticia']['link'],
        'guid' => array('url' => $noticia['Noticia']['link'], 'isPermaLink' => 'true'),
        'description' => $bodyText,
        'dc:creator' => $noticia['Usuario']['nome'],
        'pubDate' => preg_replace('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '\3-\2-\1', $noticia['Noticia']['data_noticia']),
    ));
}
