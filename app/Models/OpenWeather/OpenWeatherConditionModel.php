<?php
declare(strict_types=1);
namespace App\Models\OpenWeather;

class OpenWeatherConditionModel
{
    protected $id; // val: 500
    protected $main; // val: "Rain"
    protected $description; // val: "light rain"
    protected $icon; // val: "10d"

    public function __construct(\stdClass $data)
    {
        $this->id = $data->id;
        $this->main = $data->main;
        $this->description = $data->description;
        $this->icon = $data->icon;
    }

    public function getDescription(): string
    {
        return strtoupper($this->description);
    }

    public function getImageUrl(): string
    {
        return "https://openweathermap.org/img/wn/$this->icon@4x.png";
    }
}
