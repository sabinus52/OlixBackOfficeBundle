<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable;

use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
trait OptionsTrait
{
    /**
     * Options container.
     *
     * @var array<mixed>
     */
    protected $options;

    /**
     * The PropertyAccessor.
     *
     * @var PropertyAccessor
     */
    protected $accessor;

    // -------------------------------------------------
    // Public
    // -------------------------------------------------

    /**
     * Init optionsTrait.
     *
     * @param bool $resolve
     *
     * @return $this
     */
    public function initOptions($resolve = false)
    {
        $this->options = [];

        // @phpstan-ignore-next-line
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableMagicCall()
            ->getPropertyAccessor()
        ;

        if (true === $resolve) {
            $this->set($this->options);
        }

        return $this;
    }

    /**
     * @param array<mixed> $options
     *
     * @throws Exception
     *
     * @return $this
     */
    public function set(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
        $this->callingSettersWithOptions($this->options);

        return $this;
    }

    /**
     * Option to JSON.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function optionToJson($value)
    {
        if (\is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }

    /**
     * Validates an array whether the "template" and "vars" options are set.
     *
     * @param array<mixed> $array
     * @param array<mixed> $other
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function validateArrayForTemplateAndOther(array $array, array $other = ['template', 'vars'])
    {
        if (false === \array_key_exists('template', $array)) {
            throw new Exception('OptionsTrait::validateArrayForTemplateAndOther(): The "template" option is required.');
        }

        foreach ($array as $key => $value) {
            if (false === \in_array($key, $other, true)) {
                throw new Exception("OptionsTrait::validateArrayForTemplateAndOther(): {$key} is not an valid option.");
            }
        }

        return true;
    }

    // -------------------------------------------------
    // Helper
    // -------------------------------------------------

    /**
     * Calls the setters.
     *
     * @param array<mixed> $options
     *
     * @return $this
     */
    private function callingSettersWithOptions(array $options)
    {
        foreach ($options as $setter => $value) {
            $this->accessor->setValue($this, $setter, $value);
        }

        return $this;
    }
}
