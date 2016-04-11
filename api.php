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
$fw_regex_region = '(WW|CN|JP|TW|RKT)';
$fw_regex_version = '(\d+\.\d+\.\d+\.\d+)';

$requests = array();
$data = array();
$curl = curl_multi_init();
$running = false;

foreach ($devices as $device => $url) {
    $request = curl_init($url);
    $requests[$device] = $request;
    $data[$device] = array();

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

    foreach ($xpath->query($fw_xpath) as $element) {
        $url = $element->getAttribute("href");
        $parent = $element->parentNode->parentNode->parentNode;
        $fw = array();

        if (!$url)
            continue;

        $fw['url'] = $url;

        preg_match($fw_regex_region, $url, $matches);
        $fw['region'] = $matches[0];

        preg_match($fw_regex_version, $url, $matches);
        $fw['version'] = $matches[0];

        $tr = $parent->getElementsByTagName('tr')[1];
        $span = $parent->getElementsByTagName('span')[5];

        foreach ($span->childNodes as $child) {
            $fw['release_date'] = $child->ownerDocument->saveHtml($child);
        }

        preg_match($fw_regex_region, $url, $matches);
        $fw['region'] = $matches[0];

        preg_match($fw_regex_version, $url, $matches);
        $fw['version'] = $matches[0];

        $tr = $parent->getElementsByTagName('tr')[0];
        $span = $parent->getElementsByTagName('span')[1];

        foreach ($span->childNodes as $child) {
            $fw['description'] .= $child->ownerDocument->saveHtml($child);
        }


        array_push($data[$device], $fw);
    }
}

curl_multi_close($curl);
print(json_encode($data));
