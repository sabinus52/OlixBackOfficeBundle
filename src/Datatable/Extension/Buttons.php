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
class Buttons
{
    use OptionsTrait;

    // -------------------------------------------------
    // DataTables - Buttons Extension
    // -------------------------------------------------

    /**
     * List of built-in buttons to show.
     * Default: null.
     *
     * @var array<mixed>|null
     */
    protected $showButtons;

    /**
     * List of buttons to be created.
     * Default: null.
     *
     * @var array<mixed>|null
     */
    protected $createButtons;

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
            'show_buttons' => null,
            'create_buttons' => null,
        ]);

        $resolver->setAllowedTypes('show_buttons', ['null', 'array']);
        $resolver->setAllowedTypes('create_buttons', ['null', 'array']);

        return $this;
    }

    // -------------------------------------------------
    // Getters && Setters
    // -------------------------------------------------

    /**
     * @return array<mixed>|null
     */
    public function getShowButtons()
    {
        if (\is_array($this->showButtons)) {
            return $this->optionToJson($this->showButtons);
        }

        return $this->showButtons;
    }

    /**
     * @param array<mixed>|null $showButtons
     *
     * @return $this
     */
    public function setShowButtons($showButtons)
    {
        $this->showButtons = $showButtons;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getCreateButtons()
    {
        return $this->createButtons;
    }

    /**
     * @param array<mixed>|null $createButtons
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setCreateButtons($createButtons)
    {
        if (\is_array($createButtons)) {
            if (\count($createButtons) > 0) {
                foreach ($createButtons as $button) {
                    $newButton = new Button();
                    $this->createButtons[] = $newButton->set($button);
                }
            } else {
                throw new Exception('Buttons::setCreateButtons(): The createButtons array should contain at least one element.');
            }
        } else {
            $this->createButtons = $createButtons;
        }

        return $this;
    }
}
