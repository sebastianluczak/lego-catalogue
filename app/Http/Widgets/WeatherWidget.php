<?php
declare(strict_types=1);
namespace App\Http\Widgets;

use App\Models\OpenWeather\OpenWeatherConditionModel;
use App\Models\OpenWeather\OpenWeatherFeelsLikeModel;
use App\Models\OpenWeather\OpenWeatherTemperatureModel;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class WeatherWidget
{
    protected CarbonInterface $dt; // val: 1651744800
    protected CarbonInterface $sunrise; // val: 1651720189
    protected CarbonInterface $sunset; // val: 1651773838
    protected $moonrise; // val: 1651728900
    protected $moonset; // val: 1651702380
    protected $moon_phase; // val: 0.14

    /**
     *+"day": 17.65
    +"min": 8.75
    +"max": 20.53
    +"night": 11.59
    +"eve": 17.83
    +"morn": 11.16
     */
    protected OpenWeatherTemperatureModel $temp; // val: {#1511 ▶}

    /**
     * +"day": 16.96
     * +"night": 11.1
     * +"eve": 17.47
     * +"morn": 10.37
     */
    protected OpenWeatherFeelsLikeModel $feels_like; // val: {#1510 ▶}

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
    protected float $rain; // val: 2.28
    protected $uvi; // val: 5.25
    protected $name; // val: "Za 0 dni"

    public function __construct(\stdClass $data)
    {
        $this->dt = Carbon::createFromTimestamp($data->dt);
        $this->sunrise = Carbon::createFromTimestamp($data->sunrise);
        $this->sunset = Carbon::createFromTimestamp($data->sunset);
        $this->moonrise = $data->moonrise;
        $this->moonset = $data->moonset;
        $this->moon_phase = $data->moon_phase;
        $this->temp = new OpenWeatherTemperatureModel($data->temp);
        $this->feels_like = new OpenWeatherFeelsLikeModel($data->feels_like);
        $this->pressure = $data->pressure;
        $this->humidity = $data->humidity;
        $this->dew_point = $data->dew_point;
        $this->wind_speed = $data->wind_speed;
        $this->wind_deg = $data->wind_deg;
        $this->wind_gust = $data->wind_gust;
        $this->weatherConditionModel = new OpenWeatherConditionModel($data->weather[0]);
        $this->pop = $data->pop;
        $this->clouds = $data->clouds;
        $this->rain = $data->rain??0;
        $this->uvi = $data->uvi;
        $this->name = $data->name;
    }

    public function getDayTemperature(bool $format = true)
    {
        return $this->temp->getDayTemperature($format);
    }

    public function getNightTemperature(bool $format = true)
    {
        return $this->temp->getNightTemperature($format);
    }

    public function getFeelsLikeDayTemperature()
    {
        return $this->feels_like->getDayTemperature();
    }

    public function getFeelsLikeNightTemperature()
    {
        return $this->feels_like->getNightTemperature();
    }

    public function getDescription()
    {
        return $this->weatherConditionModel->getDescription();
    }

    public function getImageUrl()
    {
        return $this->weatherConditionModel->getImageUrl();
    }

    /**
     *
     */
    public function getWindSpeed()
    {
        return $this->wind_speed;
    }

    public function getWindGust()
    {
        return $this->wind_gust;
    }

    /**
     * Humidity, %
     */
    public function getHumidity()
    {
        return $this->humidity;
    }

    /**
     * Atmospheric pressure on the sea level, hPa
     */
    public function getPressure()
    {
        return $this->pressure;
    }

    /**
     * Rain volume, Precipitation volume, mm
     */
    public function getRain(): float
    {
        return $this->rain;
    }

    /**
     * Sunset time
     */
    public function getSunsetTime(): CarbonInterface
    {
        return $this->sunset;
    }

    /**
     * Time of the forecasted data
     */
    public function getTimeDate()
    {
        return $this->dt;
    }

    /**
     * Cloudiness, %
     */
    public function getClouds()
    {
        return $this->clouds;
    }
}
