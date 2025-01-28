Tempus Dominus Date/Time Picker
================================================================================

**Tempus Dominus Date/Time Picker** est un widget de date et heure responsive

Source : https://github.com/Eonasdan/tempus-dominus


### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\DatePickerType;
use Olix\BackOfficeBundle\Form\Type\DateTimePickerType;
use Olix\BackOfficeBundle\Form\Type\TimePickerType;

$builder
    ->add('datetime', DateTimePickerType::class, [
        'label' => 'Date et heure',
        'options_js' => [
            'default_date' => new \DateTime('2022-05-10'),
            'restrictions' => [
                'min_date' => new \DateTime('2022-05-05'),
                'disabled_dates' => [new \DateTime('2022-05-13'), new \DateTime('2022-05-15')],
                'days_of_week_disabled' => [0, 6],
            ],
            'display' => [
                'side_by_side' => true,
            ],
        ],
    ])
    ->add('date', DatePickerType::class, [
        'label' => 'Date',
        'options_js' => [
            'display' => [
                'calendar_weeks' => true,
            ],
        ],
    ])
    ->add('time', TimePickerType::class, [
        'label' => 'Heure',
    ]);
~~~

### Options Symfony

| Nom SF          | Type    | Description                                                                                  | Défaut | Valeurs 
|-----------------|---------|----------------------------------------------------------------------------------------------|--------|---------
| button_icon     | String  | Icon from right input                                                                        |        | 
| locale          | String  | Locale                                                                                       | 'fr'   |


### Options JavaScript

Ces options sont utilisées pour le widget JavaScript et sont passées en JSON.
Ces options sont à renseigner dans le paramètre `options_js` du formulaire au format *camelize*.

La liste des options disponibles est disponible dans la documentation du widget : https://getdatepicker.com/6/options/

Remarque :
- Les options de type `date` et `time` doivent être renseignées **obligatoirement** en format DateTime : `new \DateTime('2022-05-10')`
- Le nom des options est à renseigner **en underscore** : `'default_date' => new \DateTime('2022-05-10')`
