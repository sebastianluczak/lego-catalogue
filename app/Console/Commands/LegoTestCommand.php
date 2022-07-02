<?php

namespace App\Console\Commands;

use App\Models\LegoSet;
use App\Services\RebrickableService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\String\Slugger\AsciiSlugger;

class LegoTestCommand extends Command
{
    public function __construct(protected RebrickableService $rebrickableService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:set {setNumber}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RebrickableService $rebrickableService)
    {
        $legoSetView = $rebrickableService->getBySetNumber($this->argument('setNumber'));
        //$rebrickableService->getBrickLinkPriceForSet($legoSetView);
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
            'boughtPrice' => 1,
            'boughtAt' => Carbon::now(),
            'year' => $legoSetView->getYear(),
            'theme' => $theme,
        ];

        $legoSet = LegoSet::create($dataFields);
        $legoSet->save();
    }
}
