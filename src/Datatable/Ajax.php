<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class Ajax
{
    use OptionsTrait;

    // -------------------------------------------------
    // DataTables - Ajax/Data
    // -------------------------------------------------

    /**
     * URL set as the Ajax data source for the table.
     * Default: null.
     *
     * @var string|null
     */
    protected $url;

    /**
     * Send request as POST or GET.
     * Default: 'GET'.
     *
     * @var string
     */
    protected $method;

    /**
     * Data to be sent.
     * Default: null.
     *
     * @var array<mixed>|null
     */
    protected $data;

    /**
     * Use Datatables' Pipeline.
     * Default: 0 (disable).
     *
     * @see https://datatables.net/examples/server_side/pipeline.html
     *
     * @var int Number of pages to cache. Set to zero to disable feature.
     */
    protected $pipeline;

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
        $resolver->setDefaults([
            'url' => null,
            'method' => 'GET',
            'data' => null,
            'pipeline' => 10,
        ]);

        $resolver->setAllowedTypes('url', ['null', 'string']);
        $resolver->setAllowedTypes('method', 'string');
        $resolver->setAllowedTypes('data', ['null', 'array']);
        $resolver->setAllowedTypes('pipeline', 'int');

        $resolver->setAllowedValues('method', ['GET', 'POST']);

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     *
     * @deprecated Use getMethod() instead
     */
    public function getType()
    {
        return $this->getMethod();
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param mixed $method
     *
     * @return \Olix\BackOfficeBundle\Datatable\Ajax
     *
     * @deprecated Use setMethod() instead
     */
    public function setType($method)
    {
        return $this->setMethod($method);
    }

    /**
     * @return array<mixed>|null
     */
    public function getData()
    {
        if (\is_array($this->data)) {
            return $this->optionToJson($this->data);
        }

        return $this->data;
    }

    /**
     * @param array<mixed>|null $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * @param int $pipeline
     *
     * @return $this
     */
    public function setPipeline($pipeline)
    {
        $this->pipeline = $pipeline;

        return $this;
    }
}
