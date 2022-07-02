<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LegoSetRequest;
use App\Models\LegoSet;
use App\Services\RebrickableService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Money\Money;
use Prologue\Alerts\Facades\Alert;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class LegoSetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LegoSetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct(protected RebrickableService $rebrickableService)
    {
        parent::__construct();
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\LegoSet::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lego-set');
        CRUD::setEntityNameStrings('lego set', 'lego sets');

        CRUD::setShowView('lego-set.show');
    }

    public function setupShowOperation()
    {
        $this->crud->addColumn([
            'name' => 'imageUrl',
            'label' => 'Preview',
            'type' => 'image',
            'height' => "600px",
            'width' => "500px",
            'radius' => "80px"
        ]);
        $this->crud->column('setNumber')->label('Set #');
        CRUD::column('theme');
        CRUD::column('name');
        CRUD::column('year');
        CRUD::column('parts');
        CRUD::column('price');
        CRUD::column('boughtPrice')->label('Bought for');
        CRUD::column('pricediff')->label("Price Diff")->type('price-difference');
        CRUD::column('externalLink')->type('url');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'imageUrl',
            'label' => 'Preview',
            'type' => 'image',
            'height' => "200px",
            'width' => "5rem",
            'radius' => "10px"
        ]);
        $this->crud->column('setNumber')->label('Set #');
        CRUD::column('name');
        CRUD::column('theme');
        CRUD::column('year');
        CRUD::column('parts');
        CRUD::column('price')->type('price');
        CRUD::column('boughtPrice')->label('Bought for')->type('boughtPrice');
        CRUD::column('marketshare')->label("Market")->type('market');
        CRUD::column('pricediff')->label("Price Diff")->type('price-difference');


        /** @var LegoSet $model */
        $model = Crud::getModel();
        /** All of those are fire from methods in LegoSet class */
        $this->crud->addButtonFromModelFunction('top', 'estimatedValue', 'getEstimatedValue', 'end');
        $this->crud->addButtonFromModelFunction('top', 'pieces', 'getAllPiecesCount', 'first');
        $this->crud->addButtonFromModelFunction('top', 'priceperpiece', 'getPricePerPiece', 'first');

        //$this->crud->addButtonFromModelFunction('line', 'economyButton', 'getEconomyButton', 'first');
        //$this->crud->addButtonFromModelFunction('line', 'brickLinkButton', 'getBrickLinkUrl', 'first');
        $this->crud->addButtonFromModelFunction('line', 'externalLink', 'getExternalLinkAsButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'promoklockiLink', 'getPromoklockiButton', 'end');

        //$this->crud->set('rebrickableService', $this->rebrickableService);
        $this->crud->removeButton('show');
        $this->crud->addButtonFromView('top', 'wishlist', 'wishlist', 'beginning');

        if (app('request')->get('wishlist')) {
            $this->crud->addClause('where', 'boughtPrice', '=', 0);
            Alert::warning("Showing Lego wishlist");
        } else {
            $this->crud->addClause('where', 'boughtPrice', '>', 0);
            Alert::success("Showing owned Lego sets");
        }
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(LegoSetRequest::class);
        $this->crud->addField(
            [
                'name'  => 'setNumber',
                'label' => 'Numer katalogowy',
                'type'  => 'text',
            ]
        );
        $this->crud->addField(
            [
                'name'  => 'boughtPrice',
                'label' => 'Cena zakupu',
                'type'  => 'price',
                'default ' => 0
            ]
        );

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db

        /** @var LegoSet $item */
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        // Fire up model creation
        $legoSetView = $this->rebrickableService->getBySetNumber($item->getSetNumber());
        $this->rebrickableService->getPromoklockiPriceForSet($legoSetView);

        $amount = $legoSetView->getPromoklockiPrice()->getAmount();
        if ($amount == 0) {
            // try last method
            $this->rebrickableService->getBrickLinkPriceForSet($legoSetView);
            $amount = $legoSetView->getPromoklockiPrice()->getAmount();
        }
        $item->price = $amount;
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $savedRequest = $this->crud->getStrippedSaveRequest($request);

        // Fire up model creation
        $legoSetView = $this->rebrickableService->getBySetNumber($savedRequest['setNumber']);
        //$this->rebrickableService->getCeneoPriceForSet($legoSetView);
        $this->rebrickableService->getPromoklockiPriceForSet($legoSetView);

        $amount = $legoSetView->getPromoklockiPrice()->getAmount();
        if ($amount == 0) {
            // try last method
            $this->rebrickableService->getBrickLinkPriceForSet($legoSetView);
            $amount = $legoSetView->getPromoklockiPrice()->getAmount();
        }

        $theme = $this->rebrickableService->getThemeNameForThemeId($legoSetView->getThemeId());

        $dataFields = [
            'name' => $legoSetView->getName(),
            'slug' => (new AsciiSlugger())->slug($legoSetView->getName()),
            'setNumber' => $legoSetView->getSetNumber(),
            'externalLink' => $legoSetView->getExternalLink(),
            'price' => floatval($amount),
            'imageUrl' => $legoSetView->getImageUrl(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'parts' => $legoSetView->getParts(),
            'boughtPrice' => $savedRequest['boughtPrice'],
            'boughtAt' => Carbon::now(),
            'year' => $legoSetView->getYear(),
            'theme' => $theme,
        ];
        //$legoSet = LegoSet::create($dataFields);
        $item = $this->crud->create($dataFields);

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
