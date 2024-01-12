<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Twig;

use Olix\BackOfficeBundle\Datatable\Action\Action;
use Olix\BackOfficeBundle\Datatable\Column\ColumnInterface;
use Olix\BackOfficeBundle\Datatable\DatatableInterface;
use Olix\BackOfficeBundle\Datatable\Filter\FilterInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

/**
 * Runtime des "filters" et "functions" personnalisés TWIG (pour la performance).
 *
 * @see https://github.com/stwe/DatatablesBundle/blob/v1.3.0/Twig/DatatableTwigExtension.php
 */
class DatatableRuntime extends AbstractExtension
{
    /**
     * The PropertyAccessor.
     *
     * @var PropertyAccessor
     */
    protected $accessor;

    public function __construct()
    {
        $this->accessor = new PropertyAccessor();
    }

    /**
     * Renders the html template.
     *
     * @return string
     */
    public function datatablesRenderHtml(Environment $twig, DatatableInterface $datatable): string
    {
        return $twig->render('@OlixBackOffice/Datatable/datatable/datatable_html.html.twig', [
            'sg_datatables_view' => $datatable,
        ]);
    }

    /**
     * Renders the js template.
     *
     * @return string
     */
    public function datatablesRenderJs(Environment $twig, DatatableInterface $datatable): string
    {
        return $twig->render('@OlixBackOffice/Datatable/datatable/datatable_js.html.twig', [
            'sg_datatables_view' => $datatable,
        ]);
    }

    /**
     * Renders a Filter template.
     *
     * @return string
     */
    public function datatablesRenderFilter(Environment $twig, DatatableInterface $datatable, ColumnInterface $column, string $position): string
    {
        /** @var FilterInterface $filter */
        $filter = $this->accessor->getValue($column, 'filter');
        $index = $this->accessor->getValue($column, 'index');
        $searchColumn = $this->accessor->getValue($filter, 'searchColumn');

        if (null !== $searchColumn) {
            $columns = $datatable->getColumnBuilder()->getColumnNames();
            $searchColumnIndex = $columns[$searchColumn];
        } else {
            $searchColumnIndex = $index;
        }

        return $twig->render(
            $filter->getTemplate(),
            [
                'column' => $column,
                'search_column_index' => $searchColumnIndex,
                'datatable_name' => $datatable->getName(),
                'position' => $position,
            ]
        );
    }

    /**
     * Renders the MultiselectColumn Actions.
     *
     * @return string
     */
    public function datatablesRenderMultiselectActions(Environment $twig, ColumnInterface $multiselectColumn, int $pipeline): string
    {
        $parameters = [];
        $values = [];
        $actions = $this->accessor->getValue($multiselectColumn, 'actions');
        $domId = $this->accessor->getValue($multiselectColumn, 'renderActionsToId');
        $datatableName = $this->accessor->getValue($multiselectColumn, 'datatableName');

        /** @var Action $action */
        foreach ($actions as $actionKey => $action) {
            $routeParameters = $action->getRouteParameters();
            if (\is_array($routeParameters)) {
                foreach ($routeParameters as $key => $value) {
                    $parameters[$actionKey][$key] = $value;
                }
            } elseif ($routeParameters instanceof \Closure) {
                $parameters[$actionKey] = \call_user_func($routeParameters);
            } else {
                $parameters[$actionKey] = [];
            }

            if ($action->isButton()) {
                if (null !== $action->getButtonValue()) {
                    $values[$actionKey] = $action->getButtonValue();

                    if (\is_bool($values[$actionKey])) {
                        $values[$actionKey] = (int) $values[$actionKey];
                    }

                    if (true === $action->isButtonValuePrefix()) {
                        $values[$actionKey] = 'sg-datatables-'.$datatableName.'-multiselect-button-'.$actionKey.'-'.$values[$actionKey];
                    }
                } else {
                    $values[$actionKey] = null;
                }
            }
        }

        return $twig->render(
            '@OlixBackOffice/Datatable/datatable/multiselect_actions.html.twig',
            [
                'actions' => $actions,
                'route_parameters' => $parameters,
                'values' => $values,
                'datatable_name' => $datatableName,
                'dom_id' => $domId,
                'pipeline' => $pipeline,
            ]
        );
    }

    /**
     * Renders: {{ var ? 'true' : 'false' }}.
     *
     * @return string
     */
    public function boolVar(bool $value): string
    {
        if ($value) {
            return 'true';
        }

        return 'false';
    }
}
