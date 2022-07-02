<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Tests\Model;

use Olix\BackOfficeBundle\Model\MenuItemInterface;
use Olix\BackOfficeBundle\Model\MenuItemModel;
use PHPUnit\Framework\TestCase;

/**
 * Test unitaires pour la sidebar.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @covers \MenuItem
 *
 * @internal
 */
final class MenuItemTest extends TestCase
{
    /**
     * @var MenuItemInterface
     */
    protected $root;
    /**
     * @var MenuItemInterface
     */
    protected $child1;
    /**
     * @var MenuItemInterface
     */
    protected $child2;
    /**
     * @var MenuItemInterface
     */
    protected $child3;
    /**
     * @var MenuItemInterface
     */
    protected $c21;
    /**
     * @var MenuItemInterface
     */
    protected $c31;
    /**
     * @var MenuItemInterface
     */
    protected $c32;
    /**
     * @var MenuItemInterface
     */
    protected $c33;

    /**
     *           root
     *     /      |       \
     * child1  child2    child3
     *            |      /    \   \
     *           c21   c31   c32  c33 (remove).
     */
    protected function setUp(): void
    {
        $this->root = new MenuItemModel('root', []);
        $this->child1 = new MenuItemModel('child1', [
            'label' => 'Child one',
            'route' => 'olix_admin_route_child1',
            'routeArgs' => ['param1' => 1, 'param2' => 'toto'],
            'icon' => 'ico1.png',
            'iconColor' => 'red',
            'badge' => 'badge1',
            'badgeColor' => 'primary',
        ]);
        $this->child2 = new MenuItemModel('child2', [
            'label' => 'Child two',
            'icon' => 'ico2.png',
            'badge' => 'badge2',
        ]);
        $this->c21 = new MenuItemModel('c21', [
            'label' => 'Child C21',
            'route' => 'olix_admin_route_child21',
            'routeArgs' => ['param1' => 21, 'param2' => 'titi'],
            'icon' => 'ico1.png',
            'badge' => 'badge1',
        ]);
        $this->child3 = new MenuItemModel('child3', [
            'label' => 'Child tree',
            'icon' => 'ico3.png',
            'badge' => 'badge3',
        ]);
        $this->c31 = new MenuItemModel('c31', [
            'label' => 'Child one',
            'route' => 'olix_admin_route_child31',
            'routeArgs' => ['param1' => 31, 'param2' => 'titi'],
        ]);
        $this->c32 = new MenuItemModel('c32', []);
        $this->c32
            ->setLabel('Child C32')
            ->setRoute('olix_admin_route_child32')
            ->setRouteArgs(['param1' => 32, 'param2' => 'titi'])
            ->setIcon('icon32.png')
            ->setBadge(null)
        ;
        $this->c33 = new MenuItemModel('c33', []);
        $this->root
            ->addChild($this->child1)
            ->addChild($this->child2)
            ->addChild($this->child3)
        ;
        $this->child2->addChild($this->c21);
        $this->child3
            ->addChild($this->c31)
            ->addChild($this->c32)
            ->addChild($this->c33)
        ;
        $this->child3->removeChild($this->c33);
        $this->c32->setIsActive(true);
    }

    protected function tearDown(): void
    {
    }

    public function testCreateItem(): void
    {
        static::assertSame('child1', $this->child1->getCode());
        static::assertSame('Child one', $this->child1->getLabel());
        static::assertSame('olix_admin_route_child1', $this->child1->getRoute());
        static::assertSame(['param1' => 1, 'param2' => 'toto'], $this->child1->getRouteArgs());
        static::assertSame('ico1.png', $this->child1->getIcon());
        static::assertSame('red', $this->child1->getIconColor());
        static::assertSame('badge1', $this->child1->getBadge());
        static::assertSame('primary', $this->child1->getBadgeColor());
        static::assertFalse($this->child1->isActive());

        static::assertSame('Child C32', $this->c32->getLabel());
        static::assertSame('olix_admin_route_child32', $this->c32->getRoute());
        static::assertSame(['param1' => 32, 'param2' => 'titi'], $this->c32->getRouteArgs());
        static::assertSame('icon32.png', $this->c32->getIcon());
        static::assertNull($this->c32->getBadge());
        static::assertTrue($this->c32->isActive());
        static::assertTrue($this->child3->isActive());
    }

    public function testGetParent(): void
    {
        static::assertNull($this->root->getParent());
        static::assertSame($this->root, $this->child1->getParent());
        static::assertSame($this->child3, $this->c32->getParent());
    }

    public function testCountable(): void
    {
        static::assertFalse($this->child1->hasChildren());
        static::assertCount(3, $this->root);
        static::assertCount(2, $this->child3);
        // Test ajout et suppression en mode objet
        $new = new MenuItemModel('new', []);
        $this->root->addChild($new);
        static::assertCount(4, $this->root);
        $this->root->removeChild($new);
        static::assertCount(3, $this->root);
        // Test ajout et suppression en mode string
        $new = new MenuItemModel('new2', []);
        $this->root->addChild($new);
        static::assertCount(4, $this->root);
        $this->root->removeChild('new2');
        static::assertCount(3, $this->root);
    }

    public function testIterator(): void
    {
        static::assertSame($this->child2, $this->root->getChild('child2'));
        foreach ($this->root as $key => $child) {
            static::assertSame($key, $child->getCode());
        }
    }
}
