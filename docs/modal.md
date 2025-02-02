Afficher une fenêtre modale
================================================================================


## **PRÉREQUIS** : Dans le template de la page

Ajouter **impérativement** le sous template de base du modal `Modal/base.html.twig` à votre page :

~~~ twig
{# my-page.html.twig #}

{% include '@OlixBackOffice/Modal/base.html.twig' with { title: "Chargement du formulaire", class: "modal-lg" } only %}
~~~

Paramètres possibles :

- `title` : Titre du modal
- `class` : Classe CSS du modal
- `backdrop` : Fondu de la modale (exemples : `static`, `true`, `false`)


Pour afficher la fenêtre modale, il faut rajouter `href="{{ path(route_edit) }}" data-toggle="olix-modal"` dans une balise `a` ou `button` :
~~~ html
<a href="{{ path(route_edit) }}" class="btn btn-sm btn-info" data-toggle="olix-modal">Modifier</a>
<button type="button" class="btn btn-sm btn-info" data-url-load="{{ path(route_edit) }}" data-toggle="olix-modal">Modifier</button>
~~~

Attributs *data* possibles :

- `data-toggle="olix-modal"` : pour activer le plugin
- `href` ou `data-urlLoad` : URL de chargement du formulaire
- `data-urlValid` : URL de validation du formulaire si différent de `data-url-load`
- `data-target` : ID de la fenêtre modale (par défaut `#modalOlix`)



## Afficher un formulaire

Suivre les étapes de la section : [Utilisation des formulaires modales](crud/modal.md)



## Afficher une alerte

Dans un controller, ajouter la fonction suivante :

~~~ php
// src/Controller/MyController

use Olix\BackOfficeBundle\ValuesList\ModalAlert;

#[Route('/show-alert1', name: 'route_alert1')]
public function showAlert1(): Response
{
    return $this->render('@OlixBackOffice/Modal/alert.html.twig', [
        'title' => 'Alert modal',
        'color' => 'text-danger',
        'icon' => 'fas fa-info-circle',
        'message' => 'Votre message a bien été pris en compte',
    ]);
}

#[Route('/show-alert2', name: 'route_alert2')]
public function showAlert2(): Response
{
    return $this->render('@OlixBackOffice/Modal/alert.html.twig', [
        'model' => ModalAlert::ERROR,
        'message' => 'Votre message a bien été pris en compte',
    ]);
}
~~~

Soit on définit les paramètres suivants :

- `title` : Titre de l'alerte
- `color` : Couleur du texte (exemples : `text-danger`, `text-success`, `text-warning`, `text-info`)
- `icon` : Icône (exemples : `fas fa-info-circle`, `fas fa-exclamation-triangle`, `fas fa-exclamation-circle`, `fas fa-check-circle`)
- `message` : Contenu de l'alerte

Ou bien on définit un modèle depuis l'Enum `ModalAlert` :

- `model` : Enum ModalAlert (exemples : `ModalAlert::ERROR`, `ModalAlert::SUCCESS`, `ModalAlert::WARNING`, `ModalAlert::INFO`)
- `message` : Contenu de l'alerte



## Afficher une confirmation

Dans un controller, ajouter la fonction suivante en déclarant **obligatoirement** un formulaire :

~~~ php
// src/Controller/MyController

use Olix\BackOfficeBundle\ValuesList\ModalAlert;

#[Route('/show-confirm', name: 'route_confirm')]
public function showConfirm(Request $request): Response
{
    $form = $this->createFormBuilder()->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $this->addFlash('success', 'La confirmation a bien été prise en compte');

        return new Response('OK');
    }

    return $this->render('@OlixBackOffice/Modal/confirm.html.twig', [
        'form' => $form,
        'title' => [
            'label' => 'Demande de confirmation',
            'color' => 'text-warning',
        ],
        'button' => [
            'label' => 'Confirmer',
            'icon' => 'fas fa-info-circle',
            'class' => 'btn-danger',
        ],
        'message' => 'Voulez vous vraiment confirmer cette action ?',
    ]);
}
~~~

Paramètres possibles :

- `title`
  - `color` : Couleur du texte du titre (exemples : `text-danger`, `text-success`, `text-warning`, `text-info`)
  - `label` : Label du titre
- `message` : Contenu du message de la confirmation
- `button`
  - `label` : Label du bouton
  - `icon` : Icône du bouton (exemples : `fas fa-info-circle`, `fas fa-exclamation-triangle`, `fas fa-exclamation-circle`, `fas fa-check-circle`)    
  - `class` : Classe CSS du bouton (exemples : `btn-danger`, `btn-success`, `btn-warning`, `btn-info`)
