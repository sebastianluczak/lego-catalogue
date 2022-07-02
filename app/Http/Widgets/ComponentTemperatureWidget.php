<?php
declare(strict_types=1);
namespace App\Http\Widgets;

use App\Models\Collection\ComponentTemperatureRangeCollection;
use App\Models\Sensors\ComponentTemperatureRange;

class ComponentTemperatureWidget
{
    protected string $internalName;
    protected string $name;
    protected float $current;
    protected float $max;
    protected float $min;
    protected ComponentTemperatureRangeCollection $temperatures;

    public function __construct(string $componentInternalName, \stdClass $data)
    {
        $this->internalName = $componentInternalName;
        $this->name = $data->Adapter;
        unset($data->Adapter);
        $this->temperatures = new ComponentTemperatureRangeCollection();
        foreach ($data as $sensorName => $temperatureDataClass) {
            $this->temperatures->add(new ComponentTemperatureRange($sensorName, $temperatureDataClass));
        }
    }

    public function getInternalName()
    {
        return $this->internalName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTemperatures()
    {
        return $this->temperatures;
    }
}
