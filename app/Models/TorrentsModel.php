<?php
declare(strict_types=1);
namespace App\Models;

class TorrentsModel
{
    private array $torrents = [];

    public function __construct($output)
    {;
        if ($output) {
            $rows = explode(PHP_EOL, $output);
            foreach ($rows as $item) {
                $torrentInfo = explode(';;', $item);
                $this->torrents[] = $torrentInfo;
            }
        }
    }

    public function toArray(): array
    {
        return $this->torrents;
    }
}
