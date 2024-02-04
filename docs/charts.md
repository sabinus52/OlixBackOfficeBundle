# Charts

## Make class of the chart

~~~ php
// src/Chart/TestChart.php

use Olix\BackOfficeBundle\Model\ChartModel;
use Symfony\UX\Chartjs\Model\Chart;

final class TestChart extends ChartModel 
{
    public function getType(): string
    {
        return Chart::TYPE_LINE;
    }

    public function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
        ];
    }

    public function build(array $datas): void
    {
        $this
            ->setLabel(['January', 'February', 'March', 'April', 'May', 'June', 'July'])
            ->addDataSet([
                'label' => 'Cookies eaten 🍪',
                'backgroundColor' => 'rgb(255, 99, 132, .4)',
                'borderColor' => 'rgb(255, 99, 132)',
                'data' => $datas[0],
                'tension' => 0.4,
            ])
            ->addDataSet([
                'label' => 'Km walked 🏃‍♀️',
                'backgroundColor' => 'rgba(45, 220, 126, .4)',
                'borderColor' => 'rgba(45, 220, 126)',
                'data' => $datas[1],
                'tension' => 0.4,
            ])
        ;
    }
}
~~~


## In the controller

~~~ php
// ...
class ChartsController extends AbstractController
{
    #[Route(path: '/charts/js', name: 'chart_js')]
    public function viewCharts(): Response
    {
        $chart = new TestChart();

        return $this->render('charts/chartjs.html.twig', [
            'chart' => $chart->getChart([
                [2, 10, 5, 18, 20, 30, 45],
                [10, 15, 4, 3, 25, 41, 25],
            ]),
        ]);
    }
}
~~~


## In the template

~~~ html
<!-- templates/charts/chartjs.html.twig -->

<div class="card card-blue">
    <div class="card-header">
        <h3 class="card-title">Graphique de test</h3>
    </div>
    <div class="card-body">
        {{ render_chart(chart) }}
    </div>
</div>
~~~