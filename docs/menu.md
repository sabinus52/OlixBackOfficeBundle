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
            'route_args'     => array('param1' => 1, 'param2' => 'toto'),
            'icon'          => 'ico1.png',
        ]);
        $child2 = new MenuItemModel('child2', [
            'label'         => 'Child two',
            'badge'         => 'badge2',
            'badge_color'   => 'indigo',
        ]);
        $c21 = new MenuItemModel('c21', [
            'label'         => 'Child C21',
            'route'         => 'toto',
            'image'         => 'ico1.png',
        ]);
        $child2->addChild($c21);

        $event
            ->addMenuItem($child1)
            ->addMenuItem($child2);
    }
}
~~~

Options disponibles pour le constructeur de `MenuItemModel` :
- `label` : Label du menu
- `route` : Route du menu
- `route_args` : Arguments de la route
- `icon` : Icône du menu
- `icon_color` : Couleur de l'icône
- `image` : Image du menu *(à la place de l'icône)*
- `badge` : Badge du menu
- `badge_color` : Couleur du badge

Pour construire son menu avec des éléments issus de la BDD, le gestionnaire d'entité est disponible `$this->entityManager`
