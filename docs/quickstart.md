# Getting started


Create the first Controller :

## Controller `DefaultController`

~~~ bash
bin/console make:controller
~~~

~~~ php
# src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', []);
    }
}
~~~


## Template

~~~ twig
{% extends 'base_bo.html.twig' %}

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