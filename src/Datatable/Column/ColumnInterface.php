<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Column;

/**
 * Interface ColumnInterface.
 */
interface ColumnInterface
{
    /**
     * @var int
     */
    public const LAST_POSITION = -1;

    /**
     * Validates $dql. Normally a non-empty string is expected.
     *
     * @param mixed $dql
     *
     * @return bool
     */
    public function dqlConstraint($dql);

    /**
     * Specifies whether only a single column of this type is allowed (example: MultiselectColumn).
     *
     * @return bool
     */
    public function isUnique();

    /**
     * Checks whether an association is given.
     *
     * @return bool
     */
    public function isAssociation();

    /**
     * Checks whether a toMany association is given.
     *
     * @return bool
     */
    public function isToManyAssociation();

    /**
     * Use the column data value in SELECT statement.
     * Normally is it true. In case of virtual Column, multi select column or data is null is it false.
     *
     * @return bool
     */
    public function isSelectColumn();

    /**
     * Get the template, in which all DataTables-Columns-Options set.
     *
     * @return string
     */
    public function getOptionsTemplate();

    /**
     * Sometimes it is necessary to add some special data to the output array.
     * For example, the visibility of actions.
     *
     * @param array<mixed> $row
     *
     * @return $this
     */
    public function addDataToOutputArray(array &$row);

    /**
     * Render images or any other special content.
     * This function works similar to the DataTables Plugin 'columns.render'.
     *
     * @param array<mixed> $row
     *
     * @return string
     */
    public function renderCellContent(array &$row);

    /**
     * Render single field.
     *
     * @param array<mixed> $row
     *
     * @return $this
     */
    public function renderSingleField(array &$row);

    /**
     * Render toMany.
     *
     * @param array<mixed> $row
     *
     * @return $this
     */
    public function renderToMany(array &$row);

    /**
     * Get the template for the 'renderCellContent' function.
     *
     * @return string
     */
    public function getCellContentTemplate();

    /**
     * Implementation of the 'Draw Event' - fired once the table has completed a draw.
     * With this function can javascript execute after drawing the whole table.
     * Used - for example - for the Editable function.
     *
     * @return string|null
     */
    public function renderPostCreateDatatableJsContent();

    /**
     * The allowed Column positions as array.
     *
     * @return array<mixed>|null
     */
    public function allowedPositions();

    /**
     * Returns the Column type.
     *
     * @return string
     */
    public function getColumnType();

    /**
     * Does special content need to be rendered for editable?
     *
     * @param array<mixed> $row
     *
     * @return bool
     */
    public function isEditableContentRequired(array $row);
}
