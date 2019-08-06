<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ERROR );

ini_set( 'memory_limit', '128M' );
set_time_limit( 0 );

$url = "http://export.admitad.com/ru/webmaster/websites/1114971/coupons/export/?region=99&code=s2ghnw10dm&user=Vesti_ua&format=rss";
$fileContents= file_get_contents($url);
$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
$fileContents = trim(str_replace('"', "'", $fileContents));
$simpleXml = simplexml_load_string($fileContents);
$data = array();
foreach ($simpleXml->channel->item as $item)
{
    if(preg_match_all('/<img.*\/>/Uu', $item->description, $tmp)){
        $img = $tmp[0][0];
    }
    if(preg_match_all('/<p><b>.*от <a/Uu', $item->description, $tmp)){
        $description = str_replace('<b>','', $tmp[0][0]);
        $description = str_replace('<p>','', $description);
        $description = str_replace('от <a','', $description);
    }
    if(preg_match_all('/[0-9][0-9]\.[0-9][0-9]\.[0-9][0-9][0-9][0-9]/Uu', $item->description, $tmp)){
        $startDate = $tmp[0][0];
        $endDate = $tmp[0][1];
    }

    $data[] = array($item->link,$img,$description,$startDate,$endDate);
}
$formattedData = json_encode($data);
print_r($formattedData);
$filename = 'discounts.json';
$handle = fopen($filename,'w+');
fwrite($handle,$formattedData);
fclose($handle);
?>