<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\WeatherModel;

class WeatherService
{
    public function getWeatherModel(): WeatherModel
    {
        $apiKeys = [
            //'sdfsdfsdf',
            //'dsfsdfds',
            'dsfsdfsdfsdf'
        ];
        // Weather
        $weather = shell_exec('curl "https://api.openweathermap.org/data/2.5/onecall?exclude=minutely,hourly&units=metric&lat=50.049683&lon=19.944544&appid='.$apiKeys[array_rand($apiKeys)].'&lang=pl"');
        $jsonData = json_decode($weather);
        if (isset($jsonData->cod)) {
            if ($jsonData->cod === 429) {
                // api limit, meh
                \Log::critical($jsonData->message);

                throw new \Exception($jsonData->message);
            }
            if ($jsonData->cod === 401) {
                // api auth error
                \Log::critical($jsonData->message);

                throw new \Exception($jsonData->message);
            }
        }
        $weatherModel = new WeatherModel($jsonData);

        return $weatherModel;
    }
}
