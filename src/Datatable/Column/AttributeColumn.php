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
use Olix\BackOfficeBundle\Datatable\Filter\TextFilter;
use Olix\BackOfficeBundle\Datatable\Helper;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class AttributeColumn extends AbstractColumn
{
    // The AttributeColumn is filterable.
    use FilterableTrait;

    /**
     * The Attributes container.
     * A required option.
     *
     * @var Closure
     */
    protected $attributes;

    // -------------------------------------------------
    // ColumnInterface
    // -------------------------------------------------

    /**
     * {@inheritdoc}
     *
     * @param array<mixed> $row
     *
     * @return self
     */
    public function renderSingleField(array &$row)
    {
        $renderAttributes = [];

        $renderAttributes = \call_user_func($this->attributes, $row);

        $path = Helper::getDataPropertyPath($this->data);

        $content = $this->twig->render(
            $this->getCellContentTemplate(),
            [
                'attributes' => $renderAttributes,
                'data' => $this->accessor->getValue($row, $path),
            ]
        );

        // @phpstan-ignore-next-line
        $this->accessor->setValue($row, $path, $content);
    }

    /**
     * {@inheritdoc}
     *
     * @param array<mixed> $row
     */
    public function renderToMany(array &$row)
    {
        $value = null;
        $path = Helper::getDataPropertyPath($this->data, $value);

        if ($this->accessor->isReadable($row, $path)) {
            if ($this->isEditableContentRequired($row)) {
                // e.g. comments[ ].createdBy.username
                //     => $path = [comments]
                //     => $value = [createdBy][username]

                $entries = $this->accessor->getValue($row, $path);

                if (\count($entries) > 0) {
                    foreach ($entries as $key => $entry) {
                        $currentPath = $path.'['.$key.']'.$value;
                        $currentObjectPath = Helper::getPropertyPathObjectNotation($path, $key, $value);

                        /** @phpstan-ignore-next-line */
                        $content = $this->renderTemplate(
                            $this->accessor->getValue($row, $currentPath),
                            $row[$this->editable->getPk()], // @phpstan-ignore-line
                            $currentObjectPath
                        );

                        $this->accessor->setValue($row, $currentPath, $content);
                    }
                }
                // no placeholder - leave this blank
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return '@OlixBackOffice/Datatable/render/attributeColumn.html.twig';
    }

    /**
     * {@inheritdoc}
     *
     * @return string|null
     */
    public function renderPostCreateDatatableJsContent()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType()
    {
        return parent::ACTION_COLUMN;
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
            'filter' => [TextFilter::class, []],
            'attributes' => null,
        ]);

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('attributes', ['null', 'array', 'Closure']);

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
     * @param Closure $attributes
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    // -------------------------------------------------
    // Helper
    // -------------------------------------------------

    /**
     * Render template.
     *
     * @return mixed|string
     */
    private function renderTemplate(?string $data)
    {
        return $this->twig->render(
            $this->getCellContentTemplate(),
            [
                'data' => $data,
            ]
        );
    }
}
