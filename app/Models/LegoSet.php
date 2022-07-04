<?php

namespace App\Models;

use App\Services\RebrickableService;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Prologue\Alerts\Facades\Alert;

class LegoSet extends Model implements LegoSetInterface
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lego_sets';
    // protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getExternalLinkAsButton()
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="'.$this->getExternalLink().'" data-toggle="tooltip" title="External link."><i class="fa fa-search"></i> BrickSet</a>';
    }

    public function getEconomyButton()
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="https://www.brickeconomy.com/search?query='.$this->getSetNumber().'" data-toggle="tooltip" title="External link."><i class="fa fa-link"></i> BrickEconomy</a>';
    }

    public function getPromoklockiButton()
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="https://promoklocki.pl/'.$this->getSetNumber().'" data-toggle="tooltip" title="External link."><i class="fa fa-trowel-bricks"></i> Promoklocki</a>';
    }

    public function getBrickLinkUrl()
    {
        return '
            <a class="btn btn-sm btn-link"
                target="_blank" href="https://www.bricklink.com/catalogPG.asp?S='.$this->getSetNumber().'-1-&colorID=0&v=P&viewExclude=Y&cID=Y"
                data-toggle="tooltip" title="External link."
                >
                <i class="fa fa-money-bill"></i> PriceGuide
            </a>
        ';
    }

    public function getAllPiecesCount()
    {
        if (app('request')->get('wishlist')) {
            $count = LegoSet::where('boughtPrice', '=', 0)->sum('parts');
        } else {
            $count = LegoSet::where('boughtPrice', '>', 0)->sum('parts');
        }

        return '<a href="#" class="btn btn-secondary" data-style="zoom-in">
                    <span class="ladda-label"><i class="la la-calculator"></i> All parts: '.$count.'</span>
                </a>';
    }

    public function getEstimatedValue()
    {
        if (app('request')->get('wishlist')) {
            $price = LegoSet::where('boughtPrice', '=', 0)->sum('price');
        } else {
            $price = LegoSet::where('boughtPrice', '>', 0)->sum('price');
        }
        return '<a href="#" class="btn btn-success" data-style="zoom-in">
                    <span class="ladda-label"><i class="la la-dollar-sign"></i> Estimated Value: '.$price.',00 zł</span>
                </a>';
    }

    public function getPricePerPiece(): string
    {
        if (app('request')->get('wishlist')) {
            $count = LegoSet::where('boughtPrice', '=', 0)->sum('parts');
        } else {
            $count = LegoSet::where('boughtPrice', '>', 0)->sum('parts');
        }
        if (app('request')->get('wishlist')) {
            $price = LegoSet::where('boughtPrice', '=', 0)->sum('price');
        } else {
            $price = LegoSet::where('boughtPrice', '>', 0)->sum('price');
        }
        return '<a href="#" class="btn btn-success" data-style="zoom-in">
                    <span class="ladda-label"><i class="la la-dollar-sign"></i> Estimated Value: '.$price / $count.'</span>
            </a>';
    }

    public function getInstructionsLink()
    {
        //
        return '<a class="btn btn-sm btn-link" target="_blank" href="https://www.lego.com/en-us/service/buildinginstructions/search?q='.$this->getSetNumber().'" data-toggle="tooltip" title="External link."><i class="fa fa-paperclip"></i> Instructions</a>';
    }

    public function recalculatePrice(CrudPanel $crudPanel)
    {
        $rebrickableService = $crudPanel->setting('rebrickableService');

        $legoSetView = $rebrickableService->getBySetNumber($this->getSetNumber());
        $rebrickableService->getPromoklockiPriceForSet($legoSetView);

        $this->price = $legoSetView->getPromoklockiPrice()->getAmount();
        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function getSetNumber()
    {
        return $this->setNumber;
    }

    public function getCeneoPrice(): Money
    {
        // TODO: Implement getCeneoPrice() method.
    }

    public function getImageUrl(): string
    {
        // TODO: Implement getImageUrl() method.
    }

    public function getExternalLink(): string
    {
        return $this->externalLink;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
