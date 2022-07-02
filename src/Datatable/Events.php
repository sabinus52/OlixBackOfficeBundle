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
class Events
{
    use OptionsTrait;

    // -------------------------------------------------
    // DataTables - Events
    // -------------------
    // The "draw" event is already used by this bundle.
    // The "init" event is reserved.
    // -------------------------------------------------

    /**
     * Fired when the column widths are recalculated.
     *
     * @var array<mixed>|null
     */
    protected $columnSizing;

    /**
     * Fired when the visibility of a column changes.
     *
     * @var array<mixed>|null
     */
    protected $columnVisibility;

    /**
     * Fired when a table is destroyed.
     *
     * @var array<mixed>|null
     */
    protected $destroy;

    /**
     * An error has occurred during DataTables processing of data.
     *
     * @var array<mixed>|null
     */
    protected $error;

    /**
     * Fired when the page length is changed.
     *
     * @var array<mixed>|null
     */
    protected $length;

    /**
     * Fired when the data contained in the table is ordered.
     *
     * @var array<mixed>|null
     */
    protected $order;

    /**
     * Fired when the table's paging is updated.
     *
     * @var array<mixed>|null
     */
    protected $page;

    /**
     * Triggered immediately before data load.
     *
     * @var array<mixed>|null
     */
    protected $preInit;

    /**
     * Fired before an Ajax request is made.
     *
     * @var array<mixed>|null
     */
    protected $preXhr;

    /**
     * Fired when DataTables is processing data.
     *
     * @var array<mixed>|null
     */
    protected $processing;

    /**
     * Fired when the table is filtered.
     *
     * @var array<mixed>|null
     */
    protected $search;

    /**
     * Fired once state has been loaded and applied.
     *
     * @var array<mixed>|null
     */
    protected $stateLoaded;

    /**
     * Fired when loading state from storage.
     *
     * @var array<mixed>|null
     */
    protected $stateLoadParams;

    /**
     * Fired when saving table state information.
     *
     * @var array<mixed>|null
     */
    protected $stateSaveParams;

    /**
     * Fired when an Ajax request is completed.
     *
     * @var array<mixed>|null
     */
    protected $xhr;

    public function __construct()
    {
        $this->initOptions();
    }

    // -------------------------------------------------
    // Options
    // -------------------------------------------------

    /**
     * Configure options.
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'column_sizing' => null,
            'column_visibility' => null,
            'destroy' => null,
            'error' => null,
            'length' => null,
            'order' => null,
            'page' => null,
            'pre_init' => null,
            'pre_xhr' => null,
            'processing' => null,
            'search' => null,
            'state_loaded' => null,
            'state_load_params' => null,
            'state_save_params' => null,
            'xhr' => null,
        ]);

        $resolver->setAllowedTypes('column_sizing', ['null', 'array']);
        $resolver->setAllowedTypes('column_visibility', ['null', 'array']);
        $resolver->setAllowedTypes('destroy', ['null', 'array']);
        $resolver->setAllowedTypes('error', ['null', 'array']);
        $resolver->setAllowedTypes('length', ['null', 'array']);
        $resolver->setAllowedTypes('order', ['null', 'array']);
        $resolver->setAllowedTypes('page', ['null', 'array']);
        $resolver->setAllowedTypes('pre_init', ['null', 'array']);
        $resolver->setAllowedTypes('pre_xhr', ['null', 'array']);
        $resolver->setAllowedTypes('processing', ['null', 'array']);
        $resolver->setAllowedTypes('search', ['null', 'array']);
        $resolver->setAllowedTypes('state_loaded', ['null', 'array']);
        $resolver->setAllowedTypes('state_load_params', ['null', 'array']);
        $resolver->setAllowedTypes('state_save_params', ['null', 'array']);
        $resolver->setAllowedTypes('xhr', ['null', 'array']);

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @return array<mixed>|null
     */
    public function getColumnSizing()
    {
        return $this->columnSizing;
    }

    /**
     * @param array<mixed>|null $columnSizing
     *
     * @return $this
     */
    public function setColumnSizing($columnSizing)
    {
        if (\is_array($columnSizing)) {
            $this->validateArrayForTemplateAndOther($columnSizing);
        }

        $this->columnSizing = $columnSizing;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getColumnVisibility()
    {
        return $this->columnVisibility;
    }

    /**
     * @param array<mixed>|null $columnVisibility
     *
     * @return $this
     */
    public function setColumnVisibility($columnVisibility)
    {
        if (\is_array($columnVisibility)) {
            $this->validateArrayForTemplateAndOther($columnVisibility);
        }

        $this->columnVisibility = $columnVisibility;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getDestroy()
    {
        return $this->destroy;
    }

    /**
     * @param array<mixed>|null $destroy
     *
     * @return $this
     */
    public function setDestroy($destroy)
    {
        if (\is_array($destroy)) {
            $this->validateArrayForTemplateAndOther($destroy);
        }

        $this->destroy = $destroy;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param array<mixed>|null $error
     *
     * @return $this
     */
    public function setError($error)
    {
        if (\is_array($error)) {
            $this->validateArrayForTemplateAndOther($error);
        }

        $this->error = $error;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param array<mixed>|null $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        if (\is_array($length)) {
            $this->validateArrayForTemplateAndOther($length);
        }

        $this->length = $length;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param array<mixed>|null $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        if (\is_array($order)) {
            $this->validateArrayForTemplateAndOther($order);
        }

        $this->order = $order;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param array<mixed>|null $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        if (\is_array($page)) {
            $this->validateArrayForTemplateAndOther($page);
        }

        $this->page = $page;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getPreInit()
    {
        return $this->preInit;
    }

    /**
     * @param array<mixed>|null $preInit
     *
     * @return $this
     */
    public function setPreInit($preInit)
    {
        if (\is_array($preInit)) {
            $this->validateArrayForTemplateAndOther($preInit);
        }

        $this->preInit = $preInit;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getPreXhr()
    {
        return $this->preXhr;
    }

    /**
     * @param array<mixed>|null $preXhr
     *
     * @return $this
     */
    public function setPreXhr($preXhr)
    {
        if (\is_array($preXhr)) {
            $this->validateArrayForTemplateAndOther($preXhr);
        }

        $this->preXhr = $preXhr;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * @param array<mixed>|null $processing
     *
     * @return $this
     */
    public function setProcessing($processing)
    {
        if (\is_array($processing)) {
            $this->validateArrayForTemplateAndOther($processing);
        }

        $this->processing = $processing;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param array<mixed>|null $search
     *
     * @return $this
     */
    public function setSearch($search)
    {
        if (\is_array($search)) {
            $this->validateArrayForTemplateAndOther($search);
        }

        $this->search = $search;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getStateLoaded()
    {
        return $this->stateLoaded;
    }

    /**
     * @param array<mixed>|null $stateLoaded
     *
     * @return $this
     */
    public function setStateLoaded($stateLoaded)
    {
        if (\is_array($stateLoaded)) {
            $this->validateArrayForTemplateAndOther($stateLoaded);
        }

        $this->stateLoaded = $stateLoaded;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getStateLoadParams()
    {
        return $this->stateLoadParams;
    }

    /**
     * @param array<mixed>|null $stateLoadParams
     *
     * @return $this
     */
    public function setStateLoadParams($stateLoadParams)
    {
        if (\is_array($stateLoadParams)) {
            $this->validateArrayForTemplateAndOther($stateLoadParams);
        }

        $this->stateLoadParams = $stateLoadParams;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getStateSaveParams()
    {
        return $this->stateSaveParams;
    }

    /**
     * @param array<mixed>|null $stateSaveParams
     *
     * @return $this
     */
    public function setStateSaveParams($stateSaveParams)
    {
        if (\is_array($stateSaveParams)) {
            $this->validateArrayForTemplateAndOther($stateSaveParams);
        }

        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getXhr()
    {
        return $this->xhr;
    }

    /**
     * @param array<mixed>|null $xhr
     *
     * @return $this
     */
    public function setXhr($xhr)
    {
        if (\is_array($xhr)) {
            $this->validateArrayForTemplateAndOther($xhr);
        }

        $this->xhr = $xhr;

        return $this;
    }
}
