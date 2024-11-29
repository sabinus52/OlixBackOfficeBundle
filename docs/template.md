# Configuration du template


## Surcharge du layout

Il faut créer *OBLIGATOIREMENT* le fichier `templates/base_bo.html.twig` qui sert aussi pour les pages générées par le bundle

~~~ html
{% extends '@OlixBackOffice/layout.html.twig' %}

{% block stylesheets %}
    {{ parent() }}
    {#{ encore_entry_link_tags('app') }#}
{% endblock %}

{% block javascripts %}
    {{ parent() }}
    {#{ encore_entry_script_tags('app') }#}
{% endblock %}
~~~


## Liste des blocs que l'on peut surcharger

- **head** : Dans la balise `<head></head>`
- **title** : Titre de la page (`<title></title>`)
- **stylesheets** : Bloc des feuilles de styles
- **javascripts** : Bloc de chargement des scripts JS
- **preloader** : Image de préchargement de la page
- **brand** : Logo de la marque en haut à gauche
- **navbar_links** : Liste de liens textes dans la navbar (Ex: `<li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link">Home</a></li>`)
- **navbar_search** : Bloc de recherche. Créer le bloc vide pour le désactiver
- **navbar_messages** :  Bloc des messages. Créer le bloc vide pour le désactiver
- **navbar_notifications** : Bloc des notifications. Créer le bloc vide pour le désactiver
- **navbar_user** : Bloc et paramètres utilisateur
- **fullscreen** : Créer le bloc vide pour désactiver le bouton fullscreen
- **controlbar_link** : Lien au format icône pour afficher/masquer la barre de contrôle. Créer le bloc vide pour le désactiver
- **controlbar** : Contenu de la barre de contrôle. Créer le bloc vide pour le désactiver
- **sidebar_user** : Bloc utilisateur. Créer le bloc vide pour le désactiver
- **sidebar_search** : Bloc de formulaire de recherche. Créer le bloc vide pour le désactiver
- **content_title** : Titre de la page
- **content_subtitle** : Sous titre de la page
- **content_breadcrumb** : Fil d’Ariane. Créer le bloc vide pour le désactiver
- **content** : Contenu réelle da la page en cours
- **footer** : Pied de page. Créer le bloc vide pour le désactiver
