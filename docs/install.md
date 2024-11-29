# Installation of bundle

Recommended way of installing this library is through Composer.

Add in the **composer.json** for automation configuration via the Symfony Flex Composer plugin :

~~~ json
    "extra": {
        "symfony": {
            "endpoint": [
                "https://api.github.com/repos/sabinus52/symfony-recipes/contents/index.json",
                "flex://defaults"
            ]
        }
    },
~~~

~~~ bash
composer require olix/backoffice-bundle
./bin/console importmap:require olix-backoffice
~~~

If you are using Symfony Flex a recipe is included in the contrib repository, providing automatic installation and configuration.

if not using Flex, you should register the bundle ant configure it :


## Initialisation

Déclaration du bundle dans `config/bundles.php`
~~~ php
Olix\BackOfficeBundle\OlixBackOfficeBundle::class => ['all' => true],
~~~


## Configuration du bundle

Ajout des routes du bundle dans son application dans `config/routes/olix_bo.yaml`

~~~ yaml
olix_bo_routes:
    resource: '@OlixBackOfficeBundle/config/routing.yml'
~~~

Création du fichier de configuration du bundle `config/packages/olix_bo.yaml` pour modifier les options par défaut

~~~ yml
olix_back_office:
    options:
    security:
        class:
            user: App\Entity\User
~~~

[Voir les options](options.md)


## Assets

Génération des assets
~~~ bash
./bin/console assets:install
~~~

Ajout des assets depuis Import Mapper :

~~~ bash
./bin/console importmap:require olix-backoffice
~~~

Déclarer les assets javascript dans `assets/app.js` :

~~~ js
// Import des CSS
import "olix-backoffice/olixbo.min.css";
// Import du JS
import "olix-backoffice";
~~~


## Template

Créer **obligatoirement** le fichier `templates/base_bo.html.twig` pour surcharger le layout de bundle
~~~ twig
{% extends '@OlixBackOffice/layout.html.twig' %}

...
~~~

[Plus d'infos sur les templates](template.md)



## Intégration de la connexion utilisateur

Il faut obligatoirement créer l'entité `User` et `UserRepository` :

~~~ php
# src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Olix\BackOfficeBundle\Model\User as BaseUser;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends BaseUser {}
~~~

~~~ php
# src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
~~~

Et créer le template de base `templates/base_login.html.twig`
~~~ twig
{% extends '@OlixBackOffice/Security/layout.html.twig' %}

{% block login_logo %}<b>My Application</b>{% endblock %}

{% block login_message %}Connection{% endblock %}
~~~

[Plus d'informations](security.md)
