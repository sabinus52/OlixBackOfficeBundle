# Datatables

Full documentation : https://github.com/stwe/DatatablesBundle/blob/v1.3.0/Resources/doc/index.md

## Create a Datatable class

~~~ php
use App\Constants\Environment;
use App\Entity\Server;
use Olix\BackOfficeBundle\Datatable\AbstractDatatable;
use Olix\BackOfficeBundle\Datatable\Column\ActionColumn;
use Olix\BackOfficeBundle\Datatable\Column\BooleanColumn;
use Olix\BackOfficeBundle\Datatable\Column\Column;
use Olix\BackOfficeBundle\Datatable\Column\DateTimeColumn;
use Olix\BackOfficeBundle\Datatable\Column\VirtualColumn;
use Olix\BackOfficeBundle\Datatable\Filter\SelectFilter;

class ServerDatatable extends AbstractDatatable
{
    public function getLineFormatter()
    {
        $formatter = function ($row) {
            if (isset($row['operatingSystem']['name'])) {
                $row['os'] = $row['operatingSystem']['name'].' '.$row['operatingSystem']['version'];
            }
            $row['environment'] = $row['environment']->getBadge();
            return $row;
        };
        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = []): void
    {
        $this->ajax->set([]);

        $this->options->set([
            'individual_filtering' => true,
            'order' => [[0, 'asc']],
        ]);

        $this->columnBuilder
            ->add('id', Column::class, [
                'title' => 'Id',
                'searchable' => false,
            ])
            ->add('hostname', Column::class, [
                'title' => 'Hostname',
            ])
            ->add('addrip.ip', Column::class, [
                'title' => 'Adresse IP',
                'default_content' => '',
            ])
            ->add('virtual', BooleanColumn::class, [
                'title' => 'Virtuel',
            ])
            ->add('environment', Column::class, [
                'title' => 'Environnement',
                'filter' => [SelectFilter::class, [
                    'multiple' => false,
                    'cancel_button' => false,
                    'select_options' => array_merge(['' => 'Tous'], Environment::getFilters()),
                ]],
            ])
            ->add('os', VirtualColumn::class, [
                'title' => 'OS',
                'default_content' => '',
                'order_column' => 'operatingSystem.bits',
            ])
            ->add('operatingSystem.name', Column::class, [
                'visible' => false,
                'default_content' => '',
            ])
            ->add('operatingSystem.version', Column::class, [
                'visible' => false,
                'default_content' => '',
            ])
            ->add('deletedAt', DateTimeColumn::class, [
                'title' => 'Supprimé',
                'date_format' => 'L',
            ])
            ->add(null, ActionColumn::class, [
                'actions' => [
                    [
                        'route' => 'table_server_edit',
                        'icon' => 'fas fa-edit',
                        'label' => 'Edit',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => 'Edit',
                            'class' => 'btn btn-primary btn-sm',
                            'role' => 'button',
                        ],
                    ],
                    [
                        'route' => 'table_server_delete',
                        'icon' => 'fas fa-trash',
                        'label' => 'Delete',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => 'Delete',
                            'class' => 'btn btn-danger btn-sm',
                            'role' => 'button',
                        ],
                    ],
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return Server::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'server_datatable';
    }
}
~~~


## Controller

~~~ php
use App\Datatable\ServerDatatable;
use Olix\BackOfficeBundle\Datatable\Response\DatatableResponse;
use Symfony\Component\HttpFoundation\Request;

// ...

    /**
     * Lists all Server entities.
     *
     * @Route("/tables/server/list", name="table_server_list")
     */
    public function listServer(Request $request, ServerDatatable $datatable, DatatableResponse $responseService): Response
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('default/server-table.html.twig', [
            'datatable' => $datatable,
        ]);
    }
~~~


## Template

~~~ twig
{% extends 'base.html.twig' %}

{% block javascripts %}
{{ parent()}}
{{ olixbo_datatable_js(datatable) }}
{% endblock %}

{% block content %}

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des serveurs</h3>
                    </div>
                    <div class="card-body">
                        {{ olixbo_datatable_html(datatable) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}
~~~