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
        static::assertSame('not', $this->notice1->getCode());
        static::assertSame('triangle', $this->notice1->getIcon());
        static::assertSame('red', $this->notice1->getColor());
        static::assertSame('Coucou attention', $this->notice1->getMessage());
        static::assertSame('3 min', $this->notice1->getInfo());

        static::assertNull($this->notice0->getCode());
        static::assertSame('fas fa-exclamation-triangle', $this->notice0->getIcon());
        static::assertNull($this->notice0->getColor());
        static::assertSame('', $this->notice0->getMessage());
        static::assertNull($this->notice0->getInfo());
        $this->notice0->setIcon('circle');
        static::assertSame('circle', $this->notice0->getIcon());
        $this->notice0->setColor('green');
        static::assertSame('green', $this->notice0->getColor());
        $this->notice0->setMessage('Coucou attention');
        static::assertSame('Coucou attention', $this->notice0->getMessage());
        $this->notice0->setInfo('---');
        static::assertSame('---', $this->notice0->getInfo());
    }
}
