<?php
declare(strict_types=1);
namespace App\Services;

class DriveStorageService
{
    public function __construct()
    {

    }

    public function getDriveStorages(): array
    {
        $res = shell_exec('df -h | grep "/dev/"');

        $driveStorages = [];
        foreach (explode(PHP_EOL, $res) as $id => $item) {
            $items = explode(" ", $item);
            $normalizedArray = [];
            foreach ($items as $nonEmptyDrive) {
                if (!empty($nonEmptyDrive)) {
                    $normalizedArray[] = $nonEmptyDrive;
                }
            }

            if (count($normalizedArray) >= 6) {
                $driveStorages[] = [
                    'name' => $normalizedArray[0],
                    'total' => $normalizedArray[1],
                    'used' => $normalizedArray[2],
                    'free' => $normalizedArray[3],
                    'percent' => $normalizedArray[4],
                    'mount' => $normalizedArray[5]
                ];
            }
        }

        return $driveStorages;
    }
}
