# Pages d'erreur


Créer le dossier `templates/bundles/TwigBundle/Exception` pour stocker les templates d'erreur.


## Page d'erreur générique

Créer le template d'erreur générique `templates/bundles/TwigBundle/Exception/error.html.twig` avec le code suivant :

~~~ html
{% extends '@OlixBackOffice/Error/layout.html.twig' %}

{% block title %}{{ parent() }}{% endblock %}
{% block content_title %}OLIX Démo{% endblock %}
{% block copyright %}{% endblock %}
~~~

Vous pouvez surcharger les blocs suivants :
- `title` : Titre de la page (par défaut : Erreur {{ status_code }})
- `content_title` : Titre du contenu ou de l'application
- `copyright` : Contenu du copyright


# Page d'erreur 403

Créer le template d'erreur 403 `templates/bundles/TwigBundle/Exception/error403.html.twig` avec le code suivant :

~~~ html
{% extends '@OlixBackOffice/Error/error403.html.twig' %}
~~~