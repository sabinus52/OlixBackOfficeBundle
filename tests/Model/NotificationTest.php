<?php
/**
 * Tests unitaires pour les notifications
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage AdminBundle
 */

namespace Olix\BackOfficeBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Olix\BackOfficeBundle\Model\NotificationModel;
use Olix\BackOfficeBundle\Model\NotificationInterface;


class NotificationTest extends TestCase
{

    /**
     * @var NotificationInterface
     */
    protected $notice0;
    protected $notice1;


    protected function setUp(): void
    {
        $this->notice0 = new NotificationModel(null);
        $this->notice1 = new NotificationModel('not', [
            'icon'      => 'triangle',
            'color'     => 'red',
            'message'   => 'Coucou attention',
            'info'      => '3 min',
        ]);
    }


    protected function tearDown(): void
    {
        $this->notice0 = null;
        $this->notice1 = null;
    }


    public function testGetSet()
    {
        $this->assertSame('not', $this->notice1->getCode());
        $this->assertSame('triangle', $this->notice1->getIcon());
        $this->assertSame('red', $this->notice1->getColor());
        $this->assertSame('Coucou attention', $this->notice1->getMessage());
        $this->assertSame('3 min', $this->notice1->getInfo());

        $this->assertNull($this->notice0->getCode());
        $this->assertSame('fas fa-exclamation-triangle', $this->notice0->getIcon());
        $this->assertNull($this->notice0->getColor());
        $this->assertSame('', $this->notice0->getMessage());
        $this->assertNull($this->notice0->getInfo());
        $this->notice0->setIcon('circle');
        $this->assertSame('circle', $this->notice0->getIcon());
        $this->notice0->setColor('green');
        $this->assertSame('green', $this->notice0->getColor());
        $this->notice0->setMessage('Coucou attention');
        $this->assertSame('Coucou attention', $this->notice0->getMessage());
        $this->notice0->setInfo('---');
        $this->assertSame('---', $this->notice0->getInfo());
    }

}