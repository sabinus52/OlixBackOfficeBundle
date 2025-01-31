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
            'defaultDate' => new \DateTime('2022-05-10'),
            'restrictions' => [
                'minDate' => new \DateTime('2022-05-05'),
                'disabledDates' => [new \DateTime('2022-05-13'), new \DateTime('2022-05-15')],
                'daysOfWeekDisabled' => [0, 6],
            ],
            'display' => [
                'sideBySide' => true,
            ],
        ],
    ])
    ->add('date', DatePickerType::class, [
        'label' => 'Date',
        'options_js' => [
            'display' => [
                'calendarWeeks' => true,
            ],
        ],
    ])
    ->add('time', TimePickerType::class, [
        'label' => 'Heure',
    ]);
~~~

### Options Symfony

| Nom Symfony     | Type    | Description                                                                                  | Défaut | Valeurs 
|-----------------|---------|----------------------------------------------------------------------------------------------|--------|---------
| button_icon     | String  | Icon from right input                                                                        |        | 
| locale          | String  | Locale                                                                                       | 'fr'   |


### Options JavaScript

Ces options sont utilisées pour le widget JavaScript et sont passées en JSON.
Ces options sont à renseigner dans le paramètre `options_js` du formulaire.

La liste des options est disponible dans la documentation du widget : https://getdatepicker.com/6/options/

Remarque :
- Les options de type `date` et `time` doivent être renseignées **obligatoirement** en format DateTime : `new \DateTime('2022-05-10')`
