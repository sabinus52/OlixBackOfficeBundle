# Menu de la barre latérale


## Création du menu

Il faut créer le fichier `src/EventSubscriber/MenuBuilderSubscriber` qui doit hériter la classe ̀`Olix\BackOfficeBundle\EventSubscriber\MenuFactorySubscriber` avec comme exemple

~~~ php
<?php

namespace App\EventSubscriber;

use App\Model\MenuItem;
use Olix\BackOfficeBundle\Model\MenuItemModel;
use Olix\BackOfficeBundle\Event\SidebarMenuEvent;
use Olix\BackOfficeBundle\EventSubscriber\MenuFactorySubscriber;


class MenuBuilderSubscriber extends MenuFactorySubscriber
{
    public function build(SidebarMenuEvent $event): void
    {
        $child1 = new MenuItemModel('home', [
            'label'         => 'Tableau de bord',
            'route'         => 'home',
            'routeArgs'     => array('param1' => 1, 'param2' => 'toto'),
            'icon'          => 'ico1.png',
            'badge'         => 'badge1',
            'color'         => 'red',
        ]);
        $child2 = new MenuItemModel('child2', [
            'label'         => 'Child two',
            'icon'          => 'ico2.png',
            'badge'         => 'badge2',
        ]);
        $c21 = new MenuItemModel('c21', [
            'label'         => 'Child C21',
            'route'         => 'toto',
            'icon'          => 'ico1.png',
            'badge'         => 'badge1',
        ]);
        $child2->addChild($c21);

        $event
            ->addItem($child1)
            ->addItem($child2)
    }
}
~~~


## Customisation du menu

Il est possible de surcharger le modèle du menu pour ajouter des membres

~~~ php
<?php
namespace App\Model;

use Olix\BackOfficeBundle\Model\MenuItemModel;

class MenuItem extends MenuItemModel
{
    /**
     * @var string
     */
    protected $color = null;

    public function __construct(string $code, array $options = [])
    {
        parent::__construct($code, $options);
        $this->color = (isset($options['color'])) ? $options['color'] : null;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return MenuItemInterface
     */
    public function setColor(string $color): MenuItemInterface
    {
        $this->color = $color;
        return $this;
    }
}
~~~


Pour construire sont menu avec des éléments issus de la BDD, le gestionnaire d'entité est disponible `$this->entityManager`
