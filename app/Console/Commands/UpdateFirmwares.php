<?php

namespace App\Console\Commands;

use App\Category;
use App\Device;
use App\Firmware;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
    protected $description = 'Grab latest set of ASUS firmwares off their website';

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

            $headers = [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding: gzip, deflate, sdch',
                'Accept-Language: en-US,en;q=0.8,pl;q=0.6',
                'Cache-Control: max-age=0',
                'Connection: keep-alive',
                'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36'
            ];

            curl_setopt($curl, CURLOPT_URL, sprintf("%s?%d", $device->url, rand()));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_ENCODING, "gzip");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $content = curl_exec($curl);
            $text = strstr($content, "{");
            $text = substr($text, 0, strlen($text) - 1);
            @$json = json_decode($text, true);
            curl_close($curl);

            foreach ($json["Result"]["Obj"] as $category) {
                if (!$categories->contains("name", $category["Name"])) {
                    continue;
                }

                foreach ($category["Files"] as $file) {
                    $firmware = new Firmware;

                    $firmware->url = $file["DownloadUrl"]["Global"] ?: $file["DownloadUrl"]["China"];
                    $firmware->description = $file["Description"];
                    $firmware->release_date = $file["ReleaseDate"];
                    $firmware->version = $file["Version"];
                    $firmware->device()->associate($device);
                    $firmware->category()->associate($categories->where("name", $category["Name"])->first());

                    $firmware->save();
                }
            }
        }

        Cache::forever('last_update', date('Y-m-d H:i:s'));
    }
}
