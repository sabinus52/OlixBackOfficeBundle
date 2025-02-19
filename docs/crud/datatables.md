Mise en place d'un DataTable avec le bundle DataTablesBundle (table de données)
===========================================================

Github : https://github.com/omines/datatables-bundle
Full documentation : https://omines.github.io/datatables-bundle/


## Configuration du bundle

Configurer le fichier `config/packages/datatables.yaml` comme suit :

~~~ yml
datatables:
    language_from_cdn: false

    # Set options, as documented at https://datatables.net/reference/option/
    options:
        lengthMenu : [10, 25, 50, 100, 250, 500]
        pageLength: 50
        dom: "<'row' <'col-sm-6'l><'col-sm-6 text-right'f>><'row' <'col-sm-12' tr>><'row' <'col-sm-6'i><'col-sm-6 text-right'p>>"
        searching: true

    template_parameters:
        # Example classes to integrate nicely with Bootstrap 3.x
        className: 'table table-striped table-bordered table-hover data-table'
        columnFilter: null

    # You can for example override this to "tables" to keep the translation domains separated nicely
    translation_domain: 'messages'
~~~


## Création d'une classe Datatable

~~~ php
namespace App\Datatable;

use App\Entity\MyEntity;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class MyTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('id', TextColumn::class, [
                'label' => 'Id',
            ])
            ->add('hostname', TextColumn::class, [
                'label' => 'Hostname',
                'searchable' => true,
            ])
            ->add('addr_ip', TextColumn::class, [
                'label' => 'Adresse IP',
                'field' => 'addr_ip.ip',
                'searchable' => true,
            ])
            ->add('virtual', BoolColumn::class, [
                'label' => 'Virtuel',
                'trueValue' => 'yes',
                'falseValue' => 'no',
                'nullValue' => '',
            ])
            ->add('environment', TwigStringColumn::class, [
                'label' => 'Environnement',
                'searchable' => true,
                'template' => '{{ row.environment.badge|raw }}',
            ])
            ->add('os', TextColumn::class, [
                'label' => 'OS',
                'field' => 'operatingSystem.name',
                'searchable' => true,
                'render' => fn ($value, $context) => sprintf('%s (%s) %s %s', $value, $context->getOperatingSystem()->getBits(), $context->getOperatingSystem()->getVersion(), $context->getOperatingSystem()->getAdditional()),
            ])
            ->add('bdd', TextColumn::class, [
                'label' => 'SGBD',
                'field' => 'db.id',
                'operator' => '=',
                'searchable' => true,
                'data' => static fn (Server $row): string => ($row->getDataBase() instanceof DataBase) ? $row->getDataBase()->getFullName() : '',
            ])
            ->add('state', NumberColumn::class, [
                'label' => 'Statut',
                'raw' => true,
                'data' => fn ($row) => sprintf('<b>%s</b>', $row->getStateLabel()),
            ])
            ->add('deletedAt', DateTimeColumn::class, [
                'label' => 'Supprimé',
                'format' => 'd/m/Y',
            ])
            ->add('buttons', TwigColumn::class, [
                'label' => '',
                'className' => 'text-right align-middle',
                'template' => 'tables/server-buttonbar.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => MyEntity::class,
                'query' => static function (QueryBuilder $builder) use ($options): void {
                    $builder
                        ->select('server')
                        ->addSelect('os')
                        ->addSelect('db')
                        ->from(Server::class, 'server')
                        ->innerJoin('server.opSystemVersion', 'os')
                        ->leftJoin('server.dataBase', 'db')
                    ;
                    if (isset($options['state']) && true === $options['state']) {
                        $builder->andWhere('server.deletedAt IS NULL');
                    }
                },
            ])
        ;
    }
}
~~~

## Dans le contrôleur

~~~ php
use App\Datatable\MyTableType;
use Omines\DataTablesBundle\DataTableFactory;
// ...

class MyController extends AbstractController
{
    #[Route("/route/tables", name: "my_route")]
    public function index(Request $request, DataTableFactory $factory): Response
    {
        $datatable = $factory->createFromType(MyTableType::class, [], [
            'searching' => true,
        ])
            ->handleRequest($request)
        ;

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->renderForm('my_template.html.twig', [
            'datatable' => $datatable,
        ]);
    }
}
~~~


## Template de la liste des données

Pour afficher le formulaire dans une fenêtre modale, suivre cette documentation : [Utilisation des formulaires modales](modal.md)

~~~ twig
{# templates/crud-index.html.twig #}

{% extends 'base_bo.html.twig' %}

{% form_theme filter '@OlixBackOffice/Twig/form-theme-horizontal-layout.html.twig' %}

{# ... #}

{% block content %}
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste</h3>
                        <a class="btn btn-sm btn-info" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter"><i class="fas fa-filter"></i> Filter</a>
                        <div class="card-tools"><a href="{{ path('table__create') }}" class="btn btn-sm btn-success" data-toggle="olix-modal" data-target="#modalOlix"><i class="fas fa-plus"></i> Ajouter</a></div>
                    </div>
                    <div class="card-filter collapse" id="collapseFilter">
                        <div class="row">
                            <div class="col-6">
                                {{ form_row(filter.field1) }}
                            </div>
                            <div class="col-6">
                                {{ form_row(filter.field2) }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="olixDataTables">Loading...</div>
                        <script>
                            var olixDataTables = {{ datatable_settings(datatable) }};
                        </script>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {% include '@OlixBackOffice/Modal/base.html.twig' with { title: "Chargement du formulaire" } %}

{% endblock %}
~~~

Le nom pour l'identifiant et la variable doit être `olixDataTables`.

~~~ twig
{# templates/buttonbar.html.twig #}

<a href="{{ path('table__edit', {'id': row.id}) }}" class="btn btn-sm btn-info" data-toggle="olix-modal" data-target="#modalOlix"><i class="fas fa-edit"></i><span class="d-none d-md-inline">&nbsp;Modifier<span></a>
<a href="{{ path('table__delete', {'id': row.id}) }}" class="btn btn-sm btn-danger" data-toggle="olix-modal" data-target="#modalOlix"><i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;Supprimer<span></a>
~~~
