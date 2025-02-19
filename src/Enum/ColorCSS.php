<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Enum;

enum ColorCSS: string
{
    use EnumTrait;

    case BLACK = 'black';
    case BLUE = 'blue';
    case CYAN = 'cyan';
    case FUCHSIA = 'fuchsia';
    case GRAY = 'gray';
    case GRAY_DARK = 'gray-dark';
    case GREEN = 'green';
    case INDIGO = 'indigo';
    case LIME = 'lime';
    case MAROON = 'maroon';
    case NAVY = 'navy';
    case OLIVE = 'olive';
    case ORANGE = 'orange';
    case PINK = 'pink';
    case PURPLE = 'purple';
    case RED = 'red';
    case TEAL = 'teal';
    case WHITE = 'white';
    case YELLOW = 'yellow';
}
