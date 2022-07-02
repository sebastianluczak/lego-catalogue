<?php
declare(strict_types=1);
namespace App\Services;

use App\Http\Widgets\ComponentTemperatureWidget;
use App\Http\Widgets\CurrentWeatherWidget;
use App\Http\Widgets\DriveStorageWidget;
use App\Http\Widgets\WeatherWidget;
use App\Models\TorrentsModel;
use App\Models\WeatherModel;

class WidgetsService
{
    private array $driveStorages;
    private mixed $weatherGraph;
    private mixed $componentsTemperatures;
    private mixed $weatherGraphNightly;
    private array $torrentsModels;
    private WeatherModel $weatherModel;

    public function __construct(
        protected DriveStorageService $driveStorageService,
        protected SensorsService $sensorsService,
        protected WeatherService $weatherService
    )
    {
        // HDD status
        $this->driveStorages = $this->driveStorageService->getDriveStorages();
        // Component Temperature
        $this->componentsTemperatures = $this->sensorsService->getAll();

        // Weather
        $this->weatherModel = $this->weatherService->getWeatherModel();
        $this->weatherGraph = json_decode($this->weatherModel->getGraph());
        $this->weatherGraphNightly = json_decode($this->weatherModel->getGraphNightly());

        // torrents
        $torrents = shell_exec('PYTHON_QBITTORRENTAPI_DO_NOT_VERIFY_WEBUI_CERTIFICATE=1 qbittorrent-fetch');
        $torrentsModels = [];
        if ($torrents) {
            foreach (explode('\r\n', $torrents) as $torrent) {
                $torrentsModels[] = new TorrentsModel($torrent);
            }
        }
        $this->torrentsModels = $torrentsModels;
    }

    /**
     * @return DriveStorageWidget[]
     */
    public function getDiskWidgets(): array
    {
        $data = [];
        for ($i=0;$i<count($this->driveStorages);$i++) {
            $data[] = $this->getDiskWidget($i);
        }

        return $data;
    }

    /**
     * @return WeatherWidget[]
     */
    public function getWeatherWidgets(): array
    {
        $data = [];
        for ($i=0;$i<$this->weatherModel->getNumberOfDays();$i++) {
            $data[] = $this->getWeatherWidget($i);
        }

        return $data;
    }

    public function getCurrentWeatherWidgets(): array
    {
        return [ $this->getCurrentWeatherWidget() ];
    }

    protected function getDiskWidget(int $i): DriveStorageWidget
    {
        $driveStorageWidget = new DriveStorageWidget($this->driveStorages[$i]);

        return $driveStorageWidget;
    }

    public function getComponentTemperatureWidgets()
    {
        $data = [];
        foreach ($this->componentsTemperatures as $key => $componentsTemperature) {
            $data[] = $this->getComponentTemperatureWidget($key);
        }

        return $data;
    }

    protected function getComponentTemperatureWidget(mixed $key)
    {
        $componentTemperatureWidget = new ComponentTemperatureWidget($key, $this->componentsTemperatures->{$key});

        return $componentTemperatureWidget;
    }

    protected function getWeatherWidget(int $i): WeatherWidget
    {
        return new WeatherWidget($this->weatherModel->getForDay($i));
    }

    // todo change rendering in Twig resources/views/dashboard/currentWeather.blade.php
    public function getCurrentWeatherWidget(): CurrentWeatherWidget
    {
        return new CurrentWeatherWidget($this->weatherModel->getCurrent());
    }

    /**
     * @return mixed
     */
    public function getWeatherGraphNightly(): mixed
    {
        return $this->weatherGraphNightly;
    }

    /**
     * @return mixed
     */
    public function getWeatherGraph(): mixed
    {
        return $this->weatherGraph;
    }

    public function getWeatherGraphWidgets(): array
    {
        $data = [];

        $htmlPayload = '
            <div class="card-text">
                <div class="reset" id="traffic-chart"></div>
            </div>   ';
        $data[] = [
            'type'       => 'card',
            'class'   => 'card bg-warning text-white', // optional
            'content'    => [
                'body'   => $htmlPayload,
                'header' => '<i class="las la-temperature-low"></i> Temperatura dzienna'
            ],
            'wrapper'       => ['class' => 'col-sm-12 col-md-6'],
        ];

        $htmlPayload = "         <div class=\"card-text\">
             <div class=\"reset\" id=\"traffic-chart-nightly\"></div>
         </div>
<script>
var mainChart = new Chart($('#main-chart'), {
    type: 'line',
    data: {
        labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S'],
        datasets: [{
            label: 'My First dataset',
            backgroundColor: hexToRgba(getStyle('--info'), 10),
            borderColor: getStyle('--info'),
            pointHoverBackgroundColor: '#fff',
            borderWidth: 2,
            data: [165, 180, 70, 69, 77, 57, 125, 165, 172, 91, 173, 138, 155, 89, 50, 161, 65, 163, 160, 103, 114, 185, 125, 196, 183, 64, 137, 95, 112, 175]
        }, {
            label: 'My Second dataset',
            backgroundColor: 'transparent',
            borderColor: getStyle('--success'),
            pointHoverBackgroundColor: '#fff',
            borderWidth: 2,
            data: [92, 97, 80, 100, 86, 97, 83, 98, 87, 98, 93, 83, 87, 98, 96, 84, 91, 97, 88, 86, 94, 86, 95, 91, 98, 91, 92, 80, 83, 82]
        }, {
            label: 'My Third dataset',
            backgroundColor: 'transparent',
            borderColor: getStyle('--danger'),
            pointHoverBackgroundColor: '#fff',
            borderWidth: 1,
            borderDash: [8, 5],
            data: [65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65]
        }]
    },
    options: {
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                gridLines: {
 drawOnChartArea: false
                }
            }],
            yAxes: [{
                ticks: {
 beginAtZero: true,
 maxTicksLimit: 5,
 stepSize: Math.ceil(250 / 5),
 max: 250
                }
            }]
        },
        elements: {
            point: {
                radius: 0,
                hitRadius: 10,
                hoverRadius: 4,
                hoverBorderWidth: 3
            }
        }
    }
});

</script>
<script>
window.addEventListener(\"load\", function(){
    new Chartist.Line(\"#traffic-chart\", ' . $this->weatherGraph . ');
    new Chartist.Line(\"#traffic-chart-nightly\", ' . $this->weatherGraphNightly . ');

    });
</script>";
        $data[] = [
            'type'       => 'card',
            'class'   => 'card bg-primary text-white', // optional
            'content'    => [
                'body'   => $htmlPayload,
                'header' => '<i class="las la-temperature-high"></i> Temperatura nocna'
            ],
            'wrapper'       => ['class' => 'col-sm-12 col-md-6'],
        ];

        return $data;
    }

    /**
     * @return array
     */
    public function getTorrentsModels(): array
    {
        return $this->torrentsModels;
    }

    /**
     * @return WeatherModel
     */
    public function getWeatherModel(): WeatherModel
    {
        return $this->weatherModel;
    }
}
