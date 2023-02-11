# Installation du bundle

~~~ bash
composer require olix/backoffice-bundle
~~~

A rajouter dans le fichier `composer.json` :
~~~ json
"scripts": {
    "auto-scripts": {
        (...)
        "assets:adminlte %PUBLIC_DIR%": "symfony-cmd",
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
~~~
./bin/console assets:install
./bin/console assets:adminlte
./bin/console fos:js-routing:dump --format=json --target=config/routes/fos_js_routes.json
~~~

Ajout des routes du bundle dans son application dans `config/routes.yaml`

~~~ yaml
app_file:
    resource: '@OlixBackOfficeBundle/config/routing.yml'
~~~

Ajout des modules nodeJS :

~~~ bash
yarn add jquery
yarn add admin-lte
yarn add devbridge-autocomplete
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


## Assets

Déclarer les assets javascript dans `assets/app.js` :

~~~ js
const $ = require('jquery');
window.$ = window.jQuery = $;

import '/vendor/olix/backoffice-bundle/assets/olixbo.js';
~~~

Déclarer les assets stylesheets dans `assets/styles/app.css` :

~~~ css
@import '/vendor/olix/backoffice-bundle/assets/olixbo.css';
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
yarn dev
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