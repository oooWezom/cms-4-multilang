<?php
namespace Core;

use \Core\Geotargeting\SxGeo;
use \Core\Geotargeting\Yandex\Api;
use Core\QB\Database;
use Core\QB\DB;

class GeoIP
{

    public $ip;
    public $country;
    public $region;
    public $city;
    public $longitude;
    public $latitude;

    private $data = '/Core/Geotargeting/GeoBase.dat';
    private $geo;

    static $instance;

    public static function factory($ip = null)
    {
        if (static::$instance && static::$instance->ip == $ip) {
            return static::$instance;
        }
        return static::$instance = new self($ip);
    }

    public function __construct($ip = null)
    {
        $this->data = HOST . $this->data;
        $this->ip = ($ip !== null && filter_var($ip, FILTER_VALIDATE_IP)) ? $ip : $this->ip();
        $SxGeo = new SxGeo($this->data, SXGEO_BATCH | SXGEO_MEMORY);
        $data = $SxGeo->getCityFull($this->ip);
        if (!$data) {
            return false;
        }
        $this->longitude = Arr::get(Arr::get($data, 'city', []), 'lon');
        $this->latitude = Arr::get(Arr::get($data, 'city', []), 'lat');
        $api = new Api();
        $api->setPoint($this->longitude, $this->latitude);
        $api->setLimit(1)->setLang(Api::LANG_RU)->load();
        $response = $api->getResponse();
        if (!$response->getFoundCount()) {
            return false;
        }
        $response = $response->getList();
        $response = $response[0]->getData();
        if (!$response) {
            return false;
        }
        $this->geo = $response;
        $this->country = Arr::get($this->geo, 'CountryName');
        $this->region = Arr::get($this->geo, 'AdministrativeAreaName');
        $this->city = Arr::get($this->geo, 'LocalityName');
        $this->longitude = Arr::get($this->geo, 'Longitude');
        $this->latitude = Arr::get($this->geo, 'Latitude');
    }

    public static function ip()
    {
        $_server = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];
        foreach ($_server as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return null;
    }


    public function save()
    {
        $arr = explode('/', Arr::get($_SERVER, 'REQUEST_URI'));
        $ext = explode('.', end($arr));
        if ($arr[1] != 'Media' && $arr[1] != 'wezom' && $arr[1] != 'Wezom' && Route::module() != 'ajax') {
            // Save hit
            // Create/update visitor information
            $visitor = Common::factory('visitors')->getRow($this->ip, 'ip');
            if ($visitor) {
                // Update
                Common::factory('visitors')->update([
                    'last_enter' => time(),
                    'enters' => $visitor->enters + 1,
                ], $visitor->id);
            } else {
                // Create
                Common::factory('visitors')->insert([
                    'ip' => $this->ip,
                    'country' => $this->country,
                    'region' => $this->region,
                    'city' => $this->city,
                    'longitude' => $this->longitude,
                    'latitude' => $this->latitude,
                    'answer' => $this->geo ? json_encode($this->geo) : null,
                    'first_enter' => time(),
                    'last_enter' => time(),
                    'enters' => 1,
                ]);
            }

            $detect = new DeviceDetect;
            $device = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Phone') : 'Computer');

            $last_row = DB::select('url', 'updated_at', 'counter', 'id')
                ->from('visitors_hits')
                ->where('ip', '=', $this->ip)
                ->where('device', '=', $device)
                ->where('useragent', '=', Arr::get($_SERVER, 'HTTP_USER_AGENT'))
                ->order_by('id', 'DESC')
                ->limit(1)
                ->find();
            if (!$last_row || $last_row->url != Arr::get($_SERVER, 'REQUEST_URI') || $last_row->updated_at - time() > 300) {
                Common::factory('visitors_hits')->insert([
                    'ip' => $this->ip,
                    'url' => Arr::get($_SERVER, 'REQUEST_URI'),
                    'status' => function_exists('apache_response_headers') ? Arr::get(apache_response_headers(), 'Status', '200 OK') : '200 OK',
                    'device' => $device,
                    'useragent' => Arr::get($_SERVER, 'HTTP_USER_AGENT'),
                    'updated_at' => time(),
                    'created_at' => time(),
                ]);
            } else if ($last_row && $last_row->url == Arr::get($_SERVER, 'REQUEST_URI') && $last_row->updated_at - time() < 300) {
                Common::factory('visitors_hits')->update([
                    'counter' => $last_row->counter + 1,
                ], $last_row->id);
            }

            // Save referer
            $referer = Arr::get($_SERVER, 'HTTP_REFERER');
            if ($referer && strpos($referer, Arr::get($_SERVER, 'HTTP_HOST')) === false) {
                Common::factory('visitors_referers')->insert([
                    'ip' => $this->ip,
                    'url' => Arr::get($_SERVER, 'HTTP_REFERER'),
                ]);
            }
        }
    }

}
