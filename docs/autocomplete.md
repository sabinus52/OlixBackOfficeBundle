# Autocomplete

## Configuration

Change the css to **Bootstrap4**, in *assets/controllers.json* activate **true** :

~~~ json
/* "@symfony/ux-autocomplete": */
    "autoimport": {
        "tom-select/dist/css/tom-select.default.css": false,
        "tom-select/dist/css/tom-select.bootstrap4.css": true,
        "tom-select/dist/css/tom-select.bootstrap5.css": false
    }
~~~

~~~ bash
./bin/console importmap:require tom-select/dist/css/tom-select.bootstrap4.css
~~~


## Autocomplete in the form

See : https://symfony.com/bundles/ux-autocomplete/current/index.html


## Autocompletion in the sidebar

Autocompletion in the search of the sidebar in block `sidebar_search`

https://symfony.com/bundles/ux-autocomplete/current/index.html#advanced-creating-an-autocompleter-with-no-form

Choose a alias as exemple `server`

## Template

~~~ twig
<!-- base.html.twig -->
{% block sidebar_search %}
    {% include '@OlixBackOffice/Sidebar/search.html.twig' with { alias: 'server' } %}
{% endblock %}
~~~

## Script JS

~~~ javascript
// app.js

$("select[name='olix-search'").on("change", function (e) {
    if (this.value) {
        location.href = this.value;
    }
    $(this).prop("selectedIndex", 0);
});
~~~

## Service

~~~ php
use App\Entity\Server;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Routing\RouterInterface;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'server'])]
final class ServerAutocompleter implements EntityAutocompleterInterface
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function getEntityClass(): string
    {
        return Server::class;
    }

    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        return $repository
            // the alias "food" can be anything
            ->createQueryBuilder('server')
            ->andWhere('server.hostname LIKE :search')
            ->setParameter('search', '%'.$query.'%')

            // maybe do some custom filtering in all cases
            // ->andWhere('food.isHealthy = :isHealthy')
            // ->setParameter('isHealthy', true)
        ;
    }

    public function getLabel(object $entity): string
    {
        return $entity->getHostname();
    }

    public function getValue(object $entity): string
    {
        return (string) $this->router->generate('table_server__edit', ['id' => $entity->getId()]);
    }

    public function isGranted(Security $security): bool
    {
        // see the "security" option for details
        return true;
    }
}
~~~
