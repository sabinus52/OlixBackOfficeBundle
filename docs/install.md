# Installation du bundle

~~~ bash
composer require olix/backoffice-bundle
~~~

A rajouter dans le fichier `composer.json` :
~~~ json
"scripts": {
    "auto-scripts": {
        (...)
        "fos:js-routing:dump --format=json --target=config/routes/fos_js_routes.json": "symfony-cmd"
    }
}
~~~

## Initialisation

Déclaration du bundle dans `config/bundles.php`
~~~ php
Olix\BackOfficeBundle\OlixBackOfficeBundle::class => ['all' => true],
FOS\JsRoutingBundle\FOSJsRoutingBundle::class => ['all' => true],
Omines\DataTablesBundle\DataTablesBundle::class => ['all' => true],
~~~

Génération des assets
~~~ bash
./bin/console assets:install
./bin/console fos:js-routing:dump --format=json --target=config/routes/fos_js_routes.json
~~~

Ajout des routes du bundle dans son application dans `config/routes.yaml`

~~~ yaml
olix_bakoffice_routes:
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

Créer **obligatoirement** le fichier `templates/base.html.twig` pour surcharger le layout de bundle
~~~ twig
{% extends '@OlixBackOffice/layout.html.twig' %}

...
~~~

[Plus d'infos sur les templates](template.md)


## Assets

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


## Intégration de la connexion utilisateur

Il faut obligatoirement créer l'entité `User` et `UserRepository` :

~~~ php
# src/Entity/User.php
namespace App\Entity;

use Olix\BackOfficeBundle\Model\User as BaseUser;
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
use Doctrine\Persistence\ManagerRegistry;
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


## Page d'acceuil

Créer le controlleur `DefaultController`
~~~ bash
app/console make:controller
~~~

Pour le controller :
~~~ php
# src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', []);
    }
}
~~~

Pour le template :
~~~ twig
{% extends 'base.html.twig' %}

{% block title %}Hello DefaultController!{% endblock %}

{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Hello World !</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
~~~