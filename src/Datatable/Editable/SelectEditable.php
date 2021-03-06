<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Editable;

use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectEditable extends AbstractEditable
{
    /**
     * Source data for list.
     * Default: array().
     *
     * @var array<mixed>
     */
    protected $source;

    // -------------------------------------------------
    // FilterInterface
    // -------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'select';
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

        $resolver->setRequired('source');

        $resolver->setAllowedTypes('source', 'array');

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @return array<mixed>
     */
    public function getSource()
    {
        return $this->optionToJson($this->source);
    }

    /**
     * @param array<mixed> $source
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setSource(array $source)
    {
        if (empty($source)) {
            throw new Exception('SelectEditable::setSource(): The source array should contain at least one element.');
        }

        $this->source = $source;

        return $this;
    }
}
