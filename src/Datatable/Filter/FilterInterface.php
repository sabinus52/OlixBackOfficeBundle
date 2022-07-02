<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Filter;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface FilterInterface.
 *
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
interface FilterInterface
{
    /**
     * @return string
     */
    public function getTemplate();

    /**
     * Add an and condition.
     *
     * @param string $searchField
     * @param string $searchTypeOfField
     * @param int    $parameterCounter
     * @param mixed  $searchValue
     *
     * @return Andx
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, $searchTypeOfField, &$parameterCounter);
}
