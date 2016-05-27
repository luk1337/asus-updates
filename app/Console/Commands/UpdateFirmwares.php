<?php

namespace App\Console\Commands;

use App\Category;
use App\Device;
use App\Firmware;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;

class UpdateFirmwares extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firmwares:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grabs latest set of asus firmwares off their website';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $devices = Device::all();
        $categories = Category::all();

        Firmware::truncate();

        foreach ($devices as $device) {
            $curl = curl_init();
            $html = new DOMDocument;

            curl_setopt($curl, CURLOPT_URL, sprintf("%s?%d", $device->url, rand()));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);

            @$html->loadHTML(curl_exec($curl));
            $dom = new DOMXPath($html);

            curl_close($curl);

            foreach ($categories as $category) {
                $table = $this->parseTable($dom, $category->xpath);

                if ($table == null) continue;

                foreach ($table as $fw) {
                    $firmware = new Firmware;

                    $firmware->url = $fw['url'];
                    $firmware->description = $fw['description'];
                    $firmware->release_date = $fw['release_date'];
                    $firmware->version = $fw['version'];
                    $firmware->device()->associate($device);
                    $firmware->category()->associate($category);

                    $firmware->save();
                }
            }
        }
    }


    private function getParentRecursive($node, $depth) {
        for ($i = 0; $i <= $depth; $i++) {
            $node = $node->parentNode;
        }

        return $node;
    }

    private function parseTable($dom, $xpath) {
        $firmwares = array();

        foreach ($dom->query($xpath) as $element) {
            $firmware = array();
            $url = $element->getAttribute("href");
            $span_1 = $this->getParentRecursive($element, 3)->getElementsByTagName('span');
            $span_2 = $this->getParentRecursive($element, 4)->getElementsByTagName('span');

            if (!$url)
                continue;

            $firmware['url'] = $url;
            $firmware['description'] = "";

            $child = $span_1[5]->childNodes->item(0);
            $firmware['release_date'] = trim($child->ownerDocument->saveHtml($child));

            $child = $span_2[1]->childNodes->item(0);
            $firmware['version'] = trim($child->ownerDocument->saveHtml($child));

            foreach ($span_1[1]->childNodes as $child) {
                $firmware['description'] .= utf8_decode($child->ownerDocument->saveHtml($child));
            }

            array_push($firmwares, $firmware);
        }

        return $firmwares;
    }
}
