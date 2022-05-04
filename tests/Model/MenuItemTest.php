<?php
/**
 * Tests unitaires pour la sidebar
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage AdminBundle
 */

namespace Olix\BackOfficeBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Olix\BackOfficeBundle\Model\MenuItemModel;
use Olix\BackOfficeBundle\Model\MenuItemInterface;


class MenuItemTest extends TestCase
{

    /**
     * @var MenuItemInterface
     */
    protected $root;
    protected $child1;
    protected $child2;
    protected $child3;
    protected $c21;
    protected $c31;
    protected $c32;
    protected $c33;

    /**
     *           root
     *     /      |       \
     * child1  child2    child3
     *            |      /    \   \
     *           c21   c31   c32  c33 (remove)
     */
    protected function setUp(): void
    {
        $this->root = new MenuItemModel('root', []);
        $this->child1 = new MenuItemModel('child1', [
            'label'         => 'Child one',
            'route'         => 'olix_admin_route_child1',
            'routeArgs'     => array('param1' => 1, 'param2' => 'toto'),
            'icon'          => 'ico1.png',
            'iconColor'     => 'red',
            'badge'         => 'badge1',
            'badgeColor'    => 'primary',
        ]);
        $this->child2 = new MenuItemModel('child2', [
            'label'         => 'Child two',
            'icon'          => 'ico2.png',
            'badge'         => 'badge2',
        ]);
        $this->c21 = new MenuItemModel('c21', [
            'label'         => 'Child C21',
            'route'         => 'olix_admin_route_child21',
            'routeArgs'     => array('param1' => 21, 'param2' => 'titi'),
            'icon'          => 'ico1.png',
            'badge'         => 'badge1',
        ]);
        $this->child3 = new MenuItemModel('child3', [
            'label'         => 'Child tree',
            'icon'          => 'ico3.png',
            'badge'         => 'badge3',
        ]);
        $this->c31 = new MenuItemModel('c31', [
            'label'         => 'Child one',
            'route'         => 'olix_admin_route_child31',
            'routeArgs'     => array('param1' => 31, 'param2' => 'titi'),
        ]);
        $this->c32 = new MenuItemModel('c32', []);
        $this->c32
            ->setLabel('Child C32')
            ->setRoute('olix_admin_route_child32')
            ->setRouteArgs(array('param1' => 32, 'param2' => 'titi'))
            ->setIcon('icon32.png')
            ->setBadge(null);
        $this->c33 = new MenuItemModel('c33', []);
        $this->root
            ->addChild($this->child1)
            ->addChild($this->child2)
            ->addChild($this->child3);
        $this->child2->addChild($this->c21);
        $this->child3
            ->addChild($this->c31)
            ->addChild($this->c32)
            ->addChild($this->c33);
        $this->child3->removeChild($this->c33);
        $this->c32->setIsActive(true);
    }


    protected function tearDown(): void
    {
        $this->root = null;
        $this->child1 = null;
        $this->child2 = null;
        $this->c21 = null;
        $this->child3 = null;
        $this->c31 = null;
        $this->c32 = null;
    }


    public function testCreateItem()
    {
        $this->assertEquals('child1', $this->child1->getCode());
        $this->assertEquals('Child one', $this->child1->getLabel());
        $this->assertEquals('olix_admin_route_child1', $this->child1->getRoute());
        $this->assertEquals(array('param1' => 1, 'param2' => 'toto'), $this->child1->getRouteArgs());
        $this->assertEquals('ico1.png', $this->child1->getIcon());
        $this->assertEquals('red', $this->child1->getIconColor());
        $this->assertEquals('badge1', $this->child1->getBadge());
        $this->assertEquals('primary', $this->child1->getBadgeColor());
        $this->assertFalse($this->child1->isActive());

        $this->assertEquals('Child C32', $this->c32->getLabel());
        $this->assertEquals('olix_admin_route_child32', $this->c32->getRoute());
        $this->assertEquals(array('param1' => 32, 'param2' => 'titi'), $this->c32->getRouteArgs());
        $this->assertEquals('icon32.png', $this->c32->getIcon());
        $this->assertEquals(null, $this->c32->getBadge());
        $this->assertTrue($this->c32->isActive());
        $this->assertTrue($this->child3->isActive());
    }


    public function testGetParent()
    {
        $this->assertNull($this->root->getParent());
        $this->assertSame($this->root, $this->child1->getParent());
        $this->assertSame($this->child3, $this->c32->getParent());
    }


    public function testCountable()
    {
        $this->assertFalse($this->child1->hasChildren());
        $this->assertCount(3, $this->root);
        $this->assertCount(2, $this->child3);
        // Test ajout et suppression en mode objet
        $new = new MenuItemModel('new', []);
        $this->root->addChild($new);
        $this->assertCount(4, $this->root);
        $this->root->removeChild($new);
        $this->assertCount(3, $this->root);
        // Test ajout et suppression en mode string
        $new = new MenuItemModel('new2', []);
        $this->root->addChild($new);
        $this->assertCount(4, $this->root);
        $this->root->removeChild('new2');
        $this->assertCount(3, $this->root);
    }


    public function testIterator()
    {
        $this->assertSame($this->child2, $this->root->getChild('child2'));
        foreach ($this->root as $key => $child) {
            $this->assertEquals($key, $child->getCode());
        }
    }

}