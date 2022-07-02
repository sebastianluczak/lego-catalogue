<?php
declare(strict_types=1);
namespace App\Models;

use App\Models\OpenWeather\OpenWeatherConditionModel;
use App\Models\OpenWeather\OpenWeatherTemperatureModel;
use Carbon\Carbon;

class WeatherModel
{
    private $current;
    private $tomorrow;
    private $nextDays;

    public function __construct($object)
    {
        $this->current = $object->current;
        $this->tomorrow = $object->daily[1];
        $this->nextDays = $object->daily;
    }

    public function getCurrent()
    {
        $this->current->name = "Obecna temperatura";

        return $this->current;
    }

    public function getTomorrow()
    {
        $this->tomorrow->name = "Jutro";

        return $this->tomorrow;
    }

    public function getForDay(int $i)
    {
        if ($i == 0) {
            $this->nextDays[$i]->name = "Dziś"  .
                ' ' .Carbon::createFromTimestamp($this->nextDays[$i]->dt)->getTranslatedDayName();
        } else {
            $this->nextDays[$i]->name =
                Carbon::createFromTimestamp($this->nextDays[$i]->dt)->getTranslatedDayName();
        }

        return $this->nextDays[$i];
    }

    public function getGraph()
    {
        $today = Carbon::today();

        $max = 0;
        foreach ($this->nextDays as $nextDay) {
            if ($nextDay->temp->day > $max) {
                $max = $nextDay->temp->day;
            }
        }
        $min = 999999;
        foreach ($this->nextDays as $nextDay) {
            if ($nextDay->temp->day < $min) {
                $min = $nextDay->temp->day;
            }
        }

        $json = "
        {
        labels: [
            '".$today->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."',
            '".$today->addRealDays()->getTranslatedDayName()."'
            ],
        series: [
            [
            ".$this->nextDays[0]->temp->day.",
            ".$this->nextDays[1]->temp->day.",
            ".$this->nextDays[2]->temp->day.",
            ".$this->nextDays[3]->temp->day.",
            ".$this->nextDays[4]->temp->day.",
            ".$this->nextDays[5]->temp->day.",
            ".$this->nextDays[6]->temp->day.",
            ".$this->nextDays[7]->temp->day."
        ]]
    }, {
        low: ".$min.",
        high: ".$max.",
        height: 300,
        showArea: false
    }
    ";

        return json_encode($json);
    }

    public function getGraphNightly()
    {
        $tommorow = Carbon::today();
        $max = 0;
        foreach ($this->nextDays as $nextDay) {
            if ($nextDay->temp->night > $max) {
                $max = $nextDay->temp->night;
            }
        }
        $min = 999999;
        foreach ($this->nextDays as $nextDay) {
            if ($nextDay->temp->night < $min) {
                $min = $nextDay->temp->night;
            }
        }

        $json = "
        {
        labels: [
            '".$tommorow->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."',
            '".$tommorow->addRealDays()->getTranslatedDayName()."'
            ],
        series: [
            [
            ".$this->nextDays[0]->temp->night.",
            ".$this->nextDays[1]->temp->night.",
            ".$this->nextDays[2]->temp->night.",
            ".$this->nextDays[3]->temp->night.",
            ".$this->nextDays[4]->temp->night.",
            ".$this->nextDays[5]->temp->night.",
            ".$this->nextDays[6]->temp->night.",
            ".$this->nextDays[7]->temp->night."
        ]]
    }, {
        low: ".$min.",
        high: ".$max.",
        height: 300,
        showArea: false
    }
    ";

        return json_encode($json);
    }

    public function getNumberOfDays(): int
    {
        return count($this->nextDays);
    }
}
