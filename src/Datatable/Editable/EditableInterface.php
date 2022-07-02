<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Editable;

/**
 * Interface EditableInterface.
 */
interface EditableInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * Checks whether the object may be editable.
     *
     * @param array<mixed> $row
     *
     * @return bool
     */
    public function callEditableIfClosure(array $row = []);

    /**
     * @return string
     */
    public function getPk();

    /**
     * @return string
     */
    public function getEmptyText();
}
