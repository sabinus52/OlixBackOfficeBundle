<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Enum;

/**
 * Énumération des valeurs pour les alertes.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
enum ModalAlert
{
    case ERROR;
    case SUCCESS;
    case WARNING;
    case INFO;

    public function title(): string
    {
        return match ($this) {
            self::ERROR => 'Erreur',
            self::SUCCESS => 'Succès',
            self::WARNING => 'Attention',
            self::INFO => 'Information',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ERROR => 'text-danger',
            self::SUCCESS => 'text-success',
            self::WARNING => 'text-warning',
            self::INFO => 'text-info',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ERROR => 'fas fa-exclamation-triangle',
            self::SUCCESS => 'fas fa-check-circle',
            self::WARNING => 'fas fa-exclamation-circle',
            self::INFO => 'fas fa-info-circle',
        };
    }
}
