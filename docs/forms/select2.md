Select2
================================================================================

**Select2** permets une sélection personnalisable avec prise en charge de la recherche, des tags, d'un ensemble de données distants, du défilement infini et de nombreuses autres options très utilisées.

Source : https://github.com/select2/select2

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2ChoiceType;
// ...

$builder->add('ajax_ips', Select2ChoiceType::class, [
    'label' => 'Sélection IPs',
    'color' => 'red',
    'options_js' => [
        'minimumInputLength' => 2,
        'allowClear' => true,
    ]
]);
~~~

### Options Symfony

| Nom Symfony     | Type    | Description           | Défaut    | Valeurs 
|-----------------|---------|-----------------------|-----------|---------
| color           | String  | Couleur du widget     | 'default' | List `Enum\ColorCSS`

Liste des couleurs disponibles :  `blue`, `green`, `cyan`, `yellow`, `red`, `black`, `gray-dark`, `gray`, `indigo`, `navy`, `purple`, `fuchsia`, `pink`, `maroon`, `orange`, `lime`, `teal`, `olive`


### Options JavaScript

Ces options sont utilisées pour le widget JavaScript et sont passées en JSON.
Ces options sont à renseigner dans le paramètre `options_js` du formulaire.

La liste des options est disponible dans la documentation du widget : https://select2.org/configuration/options-api

Remarque :SF
- Pour le paramètre `allow_clear` activé il faut renseigner aussi le paramètre `placeholder`.



## Select2 avec AJAX remote datas


### Options Symfony

| Nom Symfony          | Type    |	Description                                                                   | Défaut    | Valeurs 
|----------------------|---------|--------------------------------------------------------------------------------|-----------|-------
| multiple             | Boolean | True pour une sélection multiple ou false pour une sélection simple.           | false     | true, false
| class                | String  | La classe de l'entité                                                          | null      |
| class_property       | String  | Le nom de la propriété utilisée pour la recherche de la requête                | null      |
| class_pkey           | String  | Le nom de la propriété utilisée pour identifier chaque élément de l'entité     | 'id'      |
| class_label          | String  | La nom de la propriété de l'entité utilisée pour récupérer le texte à afficher | null      | __toString()
| page_limit           | Integer | Nombre d'éléments affichés par page pour le défilement                         | 25        |
| allow_add            | Boolean | Option pour l'ajout d'un élément. `class_label` est requis                     | false     | true, false
| allow_add_prefix     | String  | Préfixe de l'option "Ajouter" pour l'entité                                    | 'onew:'   |
| callback             | Function| Callback via le QueryBuilder pour la récupération des résultats                | null      |

## Options Ajax

Ces options sont utilisées pour le widget JavaScript.
Ces options sont à renseigner dans le paramètre `ajax` du formulaire.

| Nom Symfony | Nom JavaScript | Type    |	Description                                                          | Défaut    | Valeurs 
|-------------|----------------|---------|-----------------------------------------------------------------------|-----------|-------
| route       | ajax / url     | String  | Route des données en AJAX                                             | olix_autocomplete_select2
| params      |                | Array   | Paramètres de la route                                                | []        | 
| scroll      |                | Boolean | True pour activer le défilement par page                              | true      | true, false
| delay       | ajax / delay   | Integer | Le nombre de millisecondes à attendre avant d'émettre la requête ajax | 250       | 
| cache       | ajax / cache   | Boolean |                                                                       | true      | true, false



### Exemple 1 simple sans paramètres supplémentaires

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
]);
~~~


### Example 2 où on peut définir une route personnalisée de la récupération des données

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
    'class_pkey' => 'id',
    'class_label' => 'ip',
    'required' => false,
    'options_js' => [
        'allowClear' => true,
        'placeholder' => 'Sélectionnez une IP',
    ],
    'ajax' => [
        'route' => 'form_test_ajax',
        'scroll' => false, // or true for scrolling by page
    ],
]);
~~~

Créer la fonction dans unCreate the fonction in a controller for return full results in AJAX
 contrôleur pour retourner les résultats complets en AJAX
~~~ php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Olix\BackOfficeBundle\Helper\AutoCompleteService;
use App\Form\MyFormType;


#Route("/address-ip/ajax", name="form_test_ajax")
public function getSearchIPs(Request $request, AutoCompleteService $autoComplete): JsonResponse
{
    $results = $autoComplete->getResults(MyFormType::class, $request);

    return $this->json($results);
}
~~~


### Example 3 où l'on peut autoriser l'ajout et la création d'un élément

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
    'class_label' => 'ip', // !!! REQUIRED
    'allow_add' => true,
    'options_js' => [
        'allowClear' => true,
        'placeholder' => 'Sélectionnez une IP',
    ],
]);
~~~


### Example 4 avec un callback

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
    // Example 1
    'callback' => static function (QueryBuilder $qb): void {
        $qb->andWhere('entity.ip LIKE \'10.%\'');
    },
    // Example 2
    'callback' => static fn (QueryBuilder $qb) => $qb->andWhere("entity.ip LIKE '10.%'"),
]);
~~~
