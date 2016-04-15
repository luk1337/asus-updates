<?php
/**
 * Created by PhpStorm.
 * User: luk
 * Date: 4/7/16
 * Time: 5:05 PM
 */

$devices = array(
    'ZE500KL' => 'http://www.asus.com/support/Download/39/1/0/20/KsY29RCpsJ2bea9O/32/',
    'ZE500KG' => 'http://www.asus.com/support/Download/39/1/0/21/xY9Hj3GM8ZlMxOR8/32/',
    'ZE550KL' => 'http://www.asus.com/support/Download/39/1/0/22/ZROQURlyupei1ZQz/32/',
    'ZE551KL' => 'http://www.asus.com/support/Download/39/1/0/23/SZCx58yhB5Jst0yI/8/',
    'ZD551KL' => 'http://www.asus.com/support/Download/39/1/0/17/a3iCfYUnl9DJAg41/32/',
    'ZE600KL' => 'http://www.asus.com/support/Download/39/1/0/24/WnVZyhpDzHw3JqH0/32/',
    'ZE601KL' => 'http://www.asus.com/support/Download/39/1/0/25/X3KCz1JmjdVkQtTo/32/'
);

$fw_xpath = '//*[@id="div_type_20"]//a';
$src_xpath = '//*[@id="div_type_30"]//a';

$fw_regex_region = '(WW|CN|JP|TW|RKT)';

$requests = array();
$data = array();
$curl = curl_multi_init();
$running = false;

$seed = isset($_COOKIE['seed']) ? $_COOKIE['seed'] : rand();
setcookie("seed", $seed, time() + 3600 * 6);

function parseTable($xpath, $query, $device, $arrayElem) {
    global $data, $fw_regex_region;

    foreach ($xpath->query($query) as $element) {
        $url = $element->getAttribute("href");
        $parent = $element->parentNode->parentNode->parentNode;
        $fw = array();

        if (!$url)
            continue;

        $fw['url'] = $url;
        $fw['description'] = "";
        $fw['region'] = "";

        $span = $parent->getElementsByTagName('span')[5];

        foreach ($span->childNodes as $child) {
            $fw['release_date'] = $child->ownerDocument->saveHtml($child);
        }

        $span = $parent->getElementsByTagName('span')[1];

        foreach ($span->childNodes as $child) {
            $fw['description'] .= utf8_decode($child->ownerDocument->saveHtml($child));
        }

        $span = $parent->parentNode->parentNode->getElementsByTagName('span')[1];

        foreach ($span->childNodes as $child) {
            $fw['version'] = trim($child->ownerDocument->saveHtml($child));
        }

        if (preg_match($fw_regex_region, $url, $matches) && $arrayElem == 'firmware') {
            $fw['region'] = $matches[0];
        }

        if (preg_match($fw_regex_region, $fw['version'], $matches)) {
            $fw['version'] = str_replace($matches[0] . "_", "", $fw['version']);
            $fw['version'] = str_replace($matches[0] . "-", "", $fw['version']);
            $fw['version'] = str_replace($matches[0], "", $fw['version']);
        }

        if (substr($fw['version'], 0, 1) == "V") {
            $fw['version'] = substr($fw['version'], 1);
        }

        array_push($data[$device][$arrayElem], $fw);
    }
}

foreach ($devices as $device => $url) {
    $request = curl_init(sprintf("%s?%d", $url, $seed));
    $requests[$device] = $request;
    $data[$device] = array(
        "firmware" => array(),
        "source_code" => array()
    );

    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    curl_multi_add_handle($curl, $requests[$device]);
}

do {
    curl_multi_exec($curl, $running);
} while ($running);

foreach ($requests as $device => $request) {
    curl_multi_remove_handle($curl, $request);

    $html = new DOMDocument;
    @$html->loadHTML(curl_multi_getcontent($request));
    $xpath = new DOMXPath($html);

    parseTable($xpath, $fw_xpath, $device, 'firmware');
    parseTable($xpath, $src_xpath, $device, 'source_code');
}

curl_multi_close($curl);
print(json_encode($data));
