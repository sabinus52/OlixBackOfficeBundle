<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Editable;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class TextareaEditable extends AbstractEditable
{
    /**
     * Number of rows in textarea.
     *
     * @var int
     */
    protected $rows;

    // -------------------------------------------------
    // FilterInterface
    // -------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'textarea';
    }

    // -------------------------------------------------
    // Options
    // -------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'rows' => 7,
        ]);

        $resolver->setAllowedTypes('rows', 'int');

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     *
     * @return $this
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }
}
