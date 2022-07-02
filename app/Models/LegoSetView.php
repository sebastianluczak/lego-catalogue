<?php

namespace App\Models;

use Cknow\Money\Money;

class LegoSetView implements LegoSetInterface
{
    private int $setNumber;
    private Money $ceneoPrice;
    private string $imageUrl;
    private string $name;
    private int $parts;
    private $year;
    private string $externalLink;
    private $themeId;
    private Money $promoklockiPrice;

    public function __construct($object)
    {
        $this->themeId = $object->theme_id;
        $this->imageUrl = $object->set_img_url;
        $this->name = $object->name;
        $this->year = $object->year;
        $this->parts = $object->num_parts;
        $this->externalLink = $object->set_url;
        $this->setNumber = intval(explode("-", $object->set_num)[0]);
    }

    public function getSetNumber()
    {
        return $this->setNumber;
    }

    public function setCeneoPrice(string $ceneoPrice)
    {
        $priceString = str_replace(",", ".", $ceneoPrice);
        $priceString = str_replace(" ", "", $priceString);
        $re = '/[1-9]+\d*\.\d+/m';
        $price = preg_match_all($re, $priceString, $matches);
        $this->ceneoPrice = Money::PLN(floatval($matches[0][0]));
    }

    public function setPromoklockiPriceForSet(string $text)
    {
        // goes in : '999,99 zł'
        $priceString = str_replace(",", ".", $text);
        $priceString = str_replace(" zł", "", $priceString);
        $re = '/[1-9]+\d*\.\d+/m';
        $price = preg_match_all($re, $priceString, $matches);
        $this->promoklockiPrice = Money::PLN(floatval($matches[0][0]));
    }

    /**
     * @return string
     */
    public function getCeneoPrice(): Money
    {
        return $this->ceneoPrice;
    }

    public function getPromoklockiPrice(): Money
    {
        if (!isset($this->promoklockiPrice)) {
            return Money::PLN(0);
        }
        
        return $this->promoklockiPrice;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getExternalLink(): string
    {
        return $this->externalLink;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getThemeId()
    {
        return $this->themeId;
    }

    public function getParts()
    {
        return $this->parts;
    }

    public function getYear()
    {
        return $this->year;
    }

}
