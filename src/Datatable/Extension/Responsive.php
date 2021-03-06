<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Extension;

use Exception;
use Olix\BackOfficeBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class Responsive
{
    use OptionsTrait;

    // -------------------------------------------------
    // DataTables - Responsive Extension
    // -------------------------------------------------

    /**
     * Responsive details options.
     * Required option.
     *
     * @var array<mixed>|bool
     */
    protected $details;

    public function __construct()
    {
        $this->initOptions();
    }

    // -------------------------------------------------
    // Options
    // -------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('details');

        $resolver->setAllowedTypes('details', ['array', 'bool']);

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @return array<mixed>|bool
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param array<mixed>|bool $details
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setDetails($details)
    {
        if (\is_array($details)) {
            foreach ($details as $key => $value) {
                if (false === \in_array($key, ['type', 'target', 'renderer', 'display'], true)) {
                    throw new Exception("Responsive::setDetails(): {$key} is not an valid option.");
                }
            }

            if (\is_array($details['renderer'])) {
                $this->validateArrayForTemplateAndOther($details['renderer']);
            }

            if (\is_array($details['display'])) {
                $this->validateArrayForTemplateAndOther($details['display']);
            }
        }

        $this->details = $details;

        return $this;
    }
}
