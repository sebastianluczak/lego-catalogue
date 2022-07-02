<?php
declare(strict_types=1);
namespace App\Models\OpenWeather;

class OpenWeatherFeelsLikeModel
{
    protected float $day; // val: 16.96
    protected float $night; // val: 11.1
    protected float $eve; // val: 17.47
    protected float $morn; // val: 10.37

    public function __construct(\stdClass $data)
    {
        $this->day = $data->day;
        $this->night = $data->night;
        $this->eve = $data->eve;
        $this->morn = $data->morn;
    }

    public function getDayTemperature(bool $format = true): mixed
    {
        if (!$format) {
            return $this->day;
        }

        return number_format($this->day, 1) . '°C';
    }

    public function getNightTemperature(bool $format = true): mixed
    {
        if (!$format) {
            return $this->night;
        }

        return number_format($this->night, 1) . '°C';
    }
}
