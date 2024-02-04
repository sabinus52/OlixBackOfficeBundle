<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

use Symfony\UX\Chartjs\Model\Chart;

/**
 * Classe d'un graphique.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
abstract class ChartModel implements ChartModelInterface
{
    protected Chart $chart;

    /**
     * @var array<mixed>
     */
    protected array $datas = [];

    public function __construct()
    {
        $this->chart = new Chart($this->getType());
    }

    /**
     * Retourne le graphique construit avec les données.
     *
     * @param array<mixed> $datas
     */
    public function getChart(array $datas): Chart
    {
        $this->chart->setOptions($this->getOptions());
        $this->build($datas);
        $this->chart->setData($this->datas);

        return $this->chart;
    }

    /**
     * Affecte les labels.
     *
     * @param array<mixed> $labels
     */
    protected function setLabel(array $labels): self
    {
        $this->datas['labels'] = $labels;

        return $this;
    }

    /**
     * Ajoute un jeu de données.
     *
     * @param array<mixed> $datas
     */
    protected function addDataSet(array $datas): self
    {
        $this->datas['datasets'][] = $datas;

        return $this;
    }
}
