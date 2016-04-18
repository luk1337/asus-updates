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

$xpaths = array(
    'EMI and Safety' => '//*[@id="div_type_38"]//a',
    'Firmware' => '//*[@id="div_type_20"]//a',
    'USB' => '//*[@id="div_type_22"]//a',
    'Utilities' => '//*[@id="div_type_14"]//a',
    'Source Code' => '//*[@id="div_type_30"]//a',
    'Manual' => '//*[@id="div_type_12"]//a'
);

$requests = array();
$data = array();
$curl = curl_multi_init();
$running = false;

$seed = isset($_COOKIE['seed']) ? $_COOKIE['seed'] : rand();
setcookie("seed", $seed, time() + 3600 * 24 * 7);

function getParentRecursive($node, $depth) {
    for ($i = 0; $i <= $depth; $i++) {
        $node = $node->parentNode;
    }

    return $node;
}

function parseTable($xpath, $query, $device, $arrayElem) {
    global $data;

    foreach ($xpath->query($query) as $element) {
        $fw = array();
        $url = $element->getAttribute("href");
        $span_1 = getParentRecursive($element, 3)->getElementsByTagName('span');
        $span_2 = getParentRecursive($element, 4)->getElementsByTagName('span');

        if (!$url)
            continue;

        $fw['url'] = $url;
        $fw['description'] = "";

        $child = $span_1[5]->childNodes->item(0);
        $fw['release_date'] = trim($child->ownerDocument->saveHtml($child));

        $child = $span_2[1]->childNodes->item(0);
        $fw['version'] = trim($child->ownerDocument->saveHtml($child));

        foreach ($span_1[1]->childNodes as $child) {
            $fw['description'] .= utf8_decode($child->ownerDocument->saveHtml($child));
        }

        array_push($data[$device][$arrayElem], $fw);
    }
}

foreach ($devices as $device => $url) {
    $request = curl_init(sprintf("%s?%d", $url, $seed));
    $requests[$device] = $request;
    $data[$device] = array();

    foreach ($xpaths as $key => $query) {
        $data[$device][$key] = array();
    }

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

    foreach ($xpaths as $key => $query) {
        parseTable($xpath, $query, $device, $key);
    }
}

curl_multi_close($curl);
print(json_encode($data));
