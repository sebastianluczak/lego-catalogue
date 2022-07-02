<?php

namespace App\Models;

use Cknow\Money\Money;

interface LegoSetInterface
{
    public function getSetNumber();

    /**
     * @return string
     */
    public function getCeneoPrice(): Money;

    /**
     * @return string
     */
    public function getImageUrl(): string;

    /**
     * @return string
     */
    public function getExternalLink(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
