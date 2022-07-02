<?php
declare(strict_types=1);
namespace App\Http\Widgets;

class DriveStorageWidget
{
  private string $name; // "/dev/sde"
  private string $total; // "251G"
  private string $used; // "4.8G"
  private string $free; //  "234G"
  private int $percent; //  2
  private string $mount; //  "/etc/hosts"

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->total = $data['total'];
        $this->used = $data['used'];
        $this->free = $data['free'];
        $this->percent = intval(str_replace("%", "", $data['percent']));
        $this->mount = $data['mount'];
    }

    /**
     * @return mixed|string
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @return mixed|string
     */
    public function getTotal(): mixed
    {
        return $this->total;
    }

    /**
     * @return mixed|string
     */
    public function getUsed(): mixed
    {
        return $this->used;
    }

    /**
     * @return mixed|string
     */
    public function getFree(): mixed
    {
        return $this->free;
    }

    /**
     * @return int
     */
    public function getPercent(): int
    {
        return $this->percent;
    }

    /**
     * @return mixed|string
     */
    public function getMount(): mixed
    {
        return $this->mount;
    }

    public function getMountPoint()
    {
        return $this->mount;
    }

    public function getUsedSize()
    {
        return $this->used;
    }

    public function getPercentage()
    {
        return $this->percent;
    }

    public function getFreePercentage()
    {
        return 100 - $this->percent;
    }

    public function getTotalSize()
    {
        return $this->total;
    }

    public function getFreeSize()
    {
        return $this->free;
    }
}
