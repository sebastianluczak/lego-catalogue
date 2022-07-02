<?php
declare(strict_types=1);
namespace App\Models\Sensors;

class ComponentTemperatureRange
{
    protected string $sensorName;
    protected float $currentSensorTemperature;
    protected float $maxSensorTemperature;
    protected float $minSensorTemperature;

    public function __construct(string $sensorName, \stdClass $componentTemperatureInfo)
    {
        $this->sensorName = $sensorName;
        $this->currentSensorTemperature = 0;
        $this->maxSensorTemperature = 90;
        $this->minSensorTemperature = 0;
        foreach ($componentTemperatureInfo as $temperatureKey => $temperatureInfo) {
            if (str_contains($temperatureKey, '_input')) {
                $this->currentSensorTemperature = $temperatureInfo;
            }
            if (str_contains($temperatureKey, '_max')) {
                if ($temperatureInfo < $this->maxSensorTemperature)
                    $this->maxSensorTemperature = $temperatureInfo;
            }
            if (str_contains($temperatureKey, '_min')) {
                $this->minSensorTemperature = $temperatureInfo;
            }
        }
    }

    public function getSensorName()
    {
        return $this->sensorName;
    }

    public function getCurrent()
    {
        return $this->currentSensorTemperature;
    }

    public function getMin()
    {
        return $this->minSensorTemperature;
    }

    public function getMax()
    {
        return $this->maxSensorTemperature;
    }
}
