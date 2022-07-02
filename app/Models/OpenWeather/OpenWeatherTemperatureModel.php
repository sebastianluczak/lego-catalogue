<?php
declare(strict_types=1);
namespace App\Models\OpenWeather;

class OpenWeatherTemperatureModel extends OpenWeatherFeelsLikeModel
{
    protected float $min; // val: 16.96
    protected float $max; // val: 11.1

    public function __construct(\stdClass $data)
    {
        parent::__construct($data);

        $this->min = $data->min;
        $this->max = $data->max;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }


}
