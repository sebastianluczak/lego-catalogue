<?php
declare(strict_types=1);
namespace App\Services;

class SensorsService
{
    public function getAll()
    {
        $res = shell_exec('sensors -j');
        // this is json in particular
        if ($res) {
            $jsonObject = json_decode($res);
            // this should be object
            if (!$jsonObject) {
                $jsonObject = json_decode($this->getTestSensorOutput());
            }
        } else {
            $jsonObject = json_decode($this->getTestSensorOutput());
        }

        return $jsonObject;
    }

    protected function getTestSensorOutput()
    {
        return '{
   "pch_cannonlake-virtual-0":{
      "Adapter": "DUMMY DATA",
      "temp1":{
         "temp1_input": 99.000
      }
   },
   "nvme-pci-0300":{
      "Adapter": "PCI adapter",
      "Composite":{
         "temp1_input": 29.850,
         "temp1_max": 81.850,
         "temp1_min": -273.150,
         "temp1_crit": 84.850,
         "temp1_alarm": 0.000
      },
      "Sensor 1":{
         "temp2_input": 29.850,
         "temp2_max": 65261.850,
         "temp2_min": -273.150
      },
      "Sensor 2":{
         "temp3_input": 35.850,
         "temp3_max": 65261.850,
         "temp3_min": -273.150
      }
   },
   "acpitz-acpi-0":{
      "Adapter": "ACPI interface",
      "temp1":{
         "temp1_input": 16.800,
         "temp1_crit": 18.800
      },
      "temp2":{
         "temp2_input": 27.800,
         "temp2_crit": 119.000
      }
   },
   "coretemp-isa-0000":{
      "Adapter": "ISA adapter",
      "Package id 0":{
         "temp1_input": 28.000,
         "temp1_max": 82.000,
         "temp1_crit": 100.000,
         "temp1_crit_alarm": 0.000
      },
      "Core 0":{
         "temp2_input": 27.000,
         "temp2_max": 82.000,
         "temp2_crit": 100.000,
         "temp2_crit_alarm": 0.000
      },
      "Core 1":{
         "temp3_input": 27.000,
         "temp3_max": 82.000,
         "temp3_crit": 100.000,
         "temp3_crit_alarm": 0.000
      },
      "Core 2":{
         "temp4_input": 25.000,
         "temp4_max": 82.000,
         "temp4_crit": 100.000,
         "temp4_crit_alarm": 0.000
      },
      "Core 3":{
         "temp5_input": 25.000,
         "temp5_max": 82.000,
         "temp5_crit": 100.000,
         "temp5_crit_alarm": 0.000
      },
      "Core 4":{
         "temp6_input": 28.000,
         "temp6_max": 82.000,
         "temp6_crit": 100.000,
         "temp6_crit_alarm": 0.000
      },
      "Core 5":{
         "temp7_input": 27.000,
         "temp7_max": 82.000,
         "temp7_crit": 100.000,
         "temp7_crit_alarm": 0.000
      }
   },
   "nvme-pci-0100":{
      "Adapter": "PCI adapter",
      "Composite":{
         "temp1_input": 42.850,
         "temp1_alarm": 0.000
      }
   }
}';
    }
}
