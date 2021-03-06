<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Action;

use Closure;
use Exception;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class MultiselectAction extends Action
{
    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @param array<mixed>|Closure|null $attributes
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $value = 'sg-datatables-'.$this->datatableName.'-multiselect-action';

        if (\is_array($attributes)) {
            if (\array_key_exists('href', $attributes)) {
                throw new Exception('MultiselectAction::setAttributes(): The href attribute is not allowed in this context.');
            }

            if (\array_key_exists('class', $attributes)) {
                $attributes['class'] = $value.' '.$attributes['class'];
            } else {
                $attributes['class'] = $value;
            }
        } else {
            $attributes['class'] = $value; // @phpstan-ignore-line
        }

        $this->attributes = $attributes;

        return $this;
    }
}
