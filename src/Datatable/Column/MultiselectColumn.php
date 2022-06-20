<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable\Column;

use Closure;
use Exception;
use Olix\BackOfficeBundle\Datatable\Action\MultiselectAction;
use Olix\BackOfficeBundle\Datatable\RenderIfTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class MultiselectColumn extends ActionColumn
{
    // Render a Checkbox only if conditions are TRUE.
    use RenderIfTrait;

    /**
     * HTML <input> Tag attributes (except 'type' and 'value').
     * Default: null.
     *
     * @var array<mixed>|null
     */
    protected $attributes;

    /**
     * A checkbox value, generated by column name.
     * Default: 'id'.
     *
     * @var string
     */
    protected $value;

    /**
     * Use the Datatable-Name as prefix for the value.
     * Default: false.
     *
     * @var bool
     */
    protected $valuePrefix;

    /**
     * Id selector where all multiselect actions are rendered.
     * Default: null ('sg-datatables-{{ sg_datatables_view.name }}-multiselect-actions').
     *
     * @var string|null
     */
    protected $renderActionsToId;

    // -------------------------------------------------
    // ColumnInterface
    // -------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function isUnique()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsTemplate()
    {
        return '@OlixBackOffice/Datatable/column/multiselect.html.twig';
    }

    /**
     * {@inheritdoc}
     *
     * @param array<mixed> $row
     *
     * @return self
     */
    public function addDataToOutputArray(array &$row)
    {
        // @phpstan-ignore-next-line
        $row['sg_datatables_cbox'] = $this->callRenderIfClosure($row);
    }

    /**
     * {@inheritdoc}
     *
     * @param array<mixed> $row
     *
     * @return self
     */
    public function renderSingleField(array &$row)
    {
        $value = $row[$this->value];

        if (\is_bool($value)) {
            $value = (int) $value;
        }

        if (true === $this->valuePrefix) {
            $value = 'sg-datatables-'.$this->getDatatableName().'-checkbox-'.$value;
        }

        // @phpstan-ignore-next-line
        $row[$this->getIndex()] = $this->twig->render(
            $this->getCellContentTemplate(),
            [
                'attributes' => $this->attributes,
                'value' => $value,
                'start_html' => $this->startHtml,
                'end_html' => $this->endHtml,
                'render_if_cbox' => $row['sg_datatables_cbox'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return '@OlixBackOffice/Datatable/render/multiselect.html.twig';
    }

    /**
     * {@inheritdoc}
     *
     * @return array<int>|null
     */
    public function allowedPositions()
    {
        return [0, self::LAST_POSITION];
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType()
    {
        return parent::MULTISELECT_COLUMN;
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
        parent::configureOptions($resolver);

        // predefined in the view as Checkbox
        $resolver->remove('title');

        $resolver->setDefaults([
            'attributes' => null,
            'value' => 'id',
            'value_prefix' => false,
            'render_actions_to_id' => null,
            'render_if' => null,
        ]);

        $resolver->setAllowedTypes('attributes', ['null', 'array']);
        $resolver->setAllowedTypes('value', 'string');
        $resolver->setAllowedTypes('value_prefix', 'bool');
        $resolver->setAllowedTypes('render_actions_to_id', ['null', 'string']);
        $resolver->setAllowedTypes('render_if', ['null', 'Closure']);

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @throws Exception
     *
     * @return $this
     */
    public function setActions(array $actions)
    {
        if (\count($actions) > 0) {
            foreach ($actions as $action) {
                $this->addAction($action);
            }
        } else {
            throw new Exception('MultiselectColumn::setActions(): The actions array should contain at least one element.');
        }

        return $this;
    }

    /**
     * Add action.
     *
     * @return $this
     */
    public function addAction(array $action)
    {
        $newAction = new MultiselectAction($this->datatableName);
        $this->actions[] = $newAction->set($action);

        return $this;
    }

    /**
     * @return array<mixed>|Closure|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array<mixed>|Closure|null $attributes
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $value = 'sg-datatables-'.$this->datatableName.'-multiselect-checkbox';

        if (\is_array($attributes)) {
            if (\array_key_exists('type', $attributes)) {
                throw new Exception('MultiselectColumn::setAttributes(): The type attribute is not supported.');
            }

            if (\array_key_exists('value', $attributes)) {
                throw new Exception('MultiselectColumn::setAttributes(): The value attribute is not supported.');
            }

            if (\array_key_exists('name', $attributes)) {
                $attributes['name'] = $attributes['name'].'[]';
            } else {
                $attributes['name'] = $value.'[]';
            }
            if (\array_key_exists('class', $attributes)) {
                $attributes['class'] = $value.' '.$attributes['class'];
            } else {
                $attributes['class'] = $value;
            }
        } else {
            $attributes['name'] = $value.'[]'; // @phpstan-ignore-line
            $attributes['class'] = $value;
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value prefix.
     *
     * @return bool
     */
    public function isValuePrefix()
    {
        return $this->valuePrefix;
    }

    /**
     * Set value prefix.
     *
     * @param bool $valuePrefix
     *
     * @return $this
     */
    public function setValuePrefix($valuePrefix)
    {
        $this->valuePrefix = $valuePrefix;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRenderActionsToId()
    {
        return $this->renderActionsToId;
    }

    /**
     * @param string|null $renderActionsToId
     *
     * @return $this
     */
    public function setRenderActionsToId($renderActionsToId)
    {
        $this->renderActionsToId = $renderActionsToId;

        return $this;
    }
}
