# Notification de la barre de navigation


## Création pour l'affichage des notifications

Il faut créer le fichier `src/EventSubscriber/NotificationSubscriber` qui doit hériter la classe ̀`Olix\BackOfficeBundle\EventSubscriber\NotificationFactorySubscriber` avec comme exemple

~~~ php
<?php

namespace App\EventSubscriber;

use Olix\BackOfficeBundle\Event\NotificationsEvent;
use Olix\BackOfficeBundle\EventSubscriber\NotificationFactorySubscriber;
use Olix\BackOfficeBundle\Model\NotificationModel;


class NotificationSubscriber extends NotificationFactorySubscriber
{
    public function build(NotificationsEvent $event): void
    {
        // Déclaration des options
        $event->setOptions([
            'max' => 4,
            'route' => 'notice_all',
            'class' => 'notifications',
        ]);

        $event->addNotification(new NotificationModel([
            'message' => 'A demo message',
            'color' => 'danger',
            'info' => '1 min',
        ]));
        $event->addNotification(new NotificationModel([
            'message' => 'Message 2',
            'route' => 'notice_all',
        ]));
        $event->addNotification(new NotificationModel([
            'message' => 'Message 3',
            'color' => 'info',
            'icon' => 'far fa-flag',
        ]));
        $event->addNotification(new NotificationModel([
            'message' => 'Message 4',
            'color' => 'warning',
        ]));
        $event->addNotification(new NotificationModel([
            'message' => 'Message 5',
            'color' => 'info',
        ]));
        $event->addNotification(new NotificationModel([
            'message' => 'Message 6',
            'color' => 'success',
        ]));
    }
}
~~~

Options disponibles pour le constructeur de `NotificationsEvent` via la fonction `setOptions` :
- `max` : Nombre max d'affichage de notifications dans la barre.
- `route` : Route vers toutes les notifications.
- `route_args` : Arguments de la route.
- `class` : Classe CSS de la boite de notification.

Options disponibles pour le constructeur de `NotificationModel` :
- `icon` : Icône de la notification
- `color` : Couleur de l'icône de la notification
- `message` : Message de la notification
- `info` : Info complémentaire de la notification
- `route` : Route de la notification
- `route_args` : Arguments de la route

Pour construire ses notifications, le gestionnaire d'entité est disponible `$this->entityManager`

Remarque :

Si on veut agrandir la boite de notification, il faut ajouter la classe `notifications` à la variable `class` dans les options de la notification.
Puis ajouter la classe `notifications` dans son fichier de styles `assets/app.css` :

~~~ css
.nav-item .notifications {
    min-width: 400px;
}
~~~