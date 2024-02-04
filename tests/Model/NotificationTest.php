<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Tests\Model;

use Olix\BackOfficeBundle\Model\NotificationModel;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaires pour la sidebar.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @covers \Notification
 *
 * @internal
 */
final class NotificationTest extends TestCase
{
    /**
     * @var NotificationModel
     */
    protected $notice0;
    /**
     * @var NotificationModel
     */
    protected $notice1;

    protected function setUp(): void
    {
        $this->notice0 = new NotificationModel(null);
        $this->notice1 = new NotificationModel('not', [
            'icon' => 'triangle',
            'color' => 'red',
            'message' => 'Coucou attention',
            'info' => '3 min',
        ]);
    }

    protected function tearDown(): void
    {
    }

    public function testGetSet(): void
    {
        self::assertSame('not', $this->notice1->getCode());
        self::assertSame('triangle', $this->notice1->getIcon());
        self::assertSame('red', $this->notice1->getColor());
        self::assertSame('Coucou attention', $this->notice1->getMessage());
        self::assertSame('3 min', $this->notice1->getInfo());

        self::assertNull($this->notice0->getCode());
        self::assertSame('fas fa-exclamation-triangle', $this->notice0->getIcon());
        self::assertNull($this->notice0->getColor());
        self::assertSame('', $this->notice0->getMessage());
        self::assertNull($this->notice0->getInfo());
        $this->notice0->setIcon('circle');
        self::assertSame('circle', $this->notice0->getIcon());
        $this->notice0->setColor('green');
        self::assertSame('green', $this->notice0->getColor());
        $this->notice0->setMessage('Coucou attention');
        self::assertSame('Coucou attention', $this->notice0->getMessage());
        $this->notice0->setInfo('---');
        self::assertSame('---', $this->notice0->getInfo());
    }
}
