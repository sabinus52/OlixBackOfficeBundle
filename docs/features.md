# Features

## Message flash

Les messages flash s'affiche sur un popin en bas en droite dans la page par défaut.

Pour afficher une alerte dans la page, il faut insérer le template comme suit :
~~~ twig
{# mytemplatepage.twig.html #}
...

<div class="container-fluid">
    {% include '@OlixBackOffice/Include/flash-messages.html.twig' %}
    ...
</div>
~~~

Pour ajouter un message, il faut utiliser les fonctions suivantes :
~~~ php
# mycontroller.php

$this->addFlash('warning', 'My name is Inigo Montoya. You killed my father, prepare to die!');
$this->addFlash('success', 'Have fun storming the castle!');
$this->addFlash('error', 'I do not think that word means what you think it means.');
~~~
