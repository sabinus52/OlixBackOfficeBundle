# Datatables

Github : https://github.com/omines/datatables-bundle
Full documentation : https://omines.github.io/datatables-bundle/

## Create a Datatable class

~~~ php
namespace App\Datatable;

use App\Entity\MyEntity;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
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
            ->add('addrip', TextColumn::class, [
                'label' => 'Adresse IP',
                'field' => 'addrip.ip',
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
            ->add('state', TextColumn::class, [
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
            ])
        ;
    }
}
~~~

## In the controller

~~~ php
use App\Datatable\MyTableType;
use Omines\DataTablesBundle\DataTableFactory;
// ...

class MyController extends AbstractController
{
    /**
     * @Route("/route/tables", name="myroute")
     */
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

        return $this->renderForm('mytemplate.html.twig', [
            'datatable' => $datatable,
        ]);
    }
}
~~~


## Template

~~~ twig
{% extends 'base.html.twig' %}

{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des serveurs</h3>
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
{% endblock %}
~~~

Le nom pour l'identifiant et la variable doit être `olixDataTables`.