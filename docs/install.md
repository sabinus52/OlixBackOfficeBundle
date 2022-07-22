# Installation du bundle

## Initialisation

Déclaration du bundle dans `config/bundles.php`
~~~ php
Olix\BackOfficeBundle\OlixBackOfficeBundle::class => ['all' => true],
FOS\JsRoutingBundle\FOSJsRoutingBundle::class => ['all' => true],
~~~

Génération des assets
~~~
./bin/console assets:install --symlink --relative
~~~

Ajout des routes du bundle dans son application dans `config/routes.yaml`

~~~ yaml
app_file:
    resource: '@OlixBackOfficeBundle/config/routing.yml'
~~~


## Configuration du bundle

Création du fichier de configuration du bundle `config/packages/olix_bo.yaml` pour modifier les options par défaut

~~~ yml
olix_back_office:
    options:
    security:
~~~

[Voir les options](options.md)


## Template

Créer **obligatoirement** le fichier `templates/base.hrml.twig` pour surcharger le layout de bundle
~~~ twig
{% extends '@OlixBackOffice/layout.html.twig' %}

...
~~~

[Plus d'infos sur les templates](template.md)


## Intégration de la connexion utilisateur

Il faut obligatoirement créer l'entité `User` et `UserRepository` :

~~~ php
# src/Entity/User.php

use Olix\BackOfficeBundle\Entity\User as BaseUser;
...

class User extends BaseUser
{
}
~~~

~~~ php
# src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
...

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
~~~

[Plus d'informations](security.md)
