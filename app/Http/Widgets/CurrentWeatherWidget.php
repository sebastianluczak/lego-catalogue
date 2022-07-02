<?php
declare(strict_types=1);
namespace App\Http\Widgets;

use App\Models\OpenWeather\OpenWeatherConditionModel;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class CurrentWeatherWidget
{
    protected CarbonInterface $dt; // val: 1651744800
    protected CarbonInterface $sunrise; // val: 1651720189
    protected CarbonInterface $sunset; // val: 1651773838
    protected $temp;
    protected $feels_like; // val: {#1510 ▶}

    protected $pressure; // val: 1020
    protected $humidity; // val: 57
    protected $dew_point; // val: 9.04
    protected $wind_speed; // val: 2.59
    protected $wind_deg; // val: 133
    protected $wind_gust; // val: 2.65

    /**
     * @var "weather": array:1 [▼
    0 => {#1509 ▼
    +"id": 500
    +"main": "Rain"
    +"description": "light rain"
    +"icon": "10d"
    }
    ]
     */
    protected OpenWeatherConditionModel $weatherConditionModel; // val: array:1 [▶]
    protected $clouds; // val: 22
    protected $pop; // val: 0.89
    protected $rain; // val: 2.28
    protected $uvi; // val: 5.25
    protected $name; // val: "Za 0 dni"

    public function __construct(\stdClass $data)
    {
        $this->dt = Carbon::createFromTimestamp($data->dt);
        $this->sunrise = Carbon::createFromTimestamp($data->sunrise);
        $this->sunset = Carbon::createFromTimestamp($data->sunset);
        $this->temp = $data->temp;
        $this->feels_like = $data->feels_like;
        $this->pressure = $data->pressure;
        $this->humidity = $data->humidity;
        $this->dew_point = $data->dew_point;
        $this->wind_speed = $data->wind_speed;
        $this->wind_deg = $data->wind_deg;
        $this->weatherConditionModel = new OpenWeatherConditionModel($data->weather[0]);
        $this->clouds = $data->clouds;
        $this->rain = $data->rain->{"1h"}??0;
        $this->uvi = $data->uvi;
        $this->name = "Obecna pogoda";
    }

    public function getCurrentTemperature()
    {
        return $this->temp;
    }

    public function getFeelsLikeTemperature()
    {
        return $this->feels_like;
    }

    public function getDescription()
    {
        return $this->weatherConditionModel->getDescription();
    }

    public function getImageUrl()
    {
        return $this->weatherConditionModel->getImageUrl();
    }

    public function getWindSpeed()
    {
        return $this->wind_speed;
    }

    public function getWindGust()
    {
        return $this->wind_gust;
    }

    public function getHumidity()
    {
        return $this->humidity;
    }

    public function getPressure()
    {
        return $this->pressure;
    }

    public function getRain()
    {
        return $this->rain;
    }

    public function getSunsetTime()
    {
        return $this->sunset;
    }
}
