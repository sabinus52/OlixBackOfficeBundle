# Autocomplete

Autocompletion in the search of the sidebar in block `sidebar_search`

## Script JS

~~~ javascript
var engine = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: Routing.generate('autocomplete-route', { term: '' }),
    remote: {
        url: Routing.generate('autocomplete-route', { term: 'TERM' }),
        wildcard: 'TERM'
    }
});
    
$('#sdSearch').typeahead(null, {
    name: 'myautocomplete',
    display: 'value',
    source: engine
});
~~~

## Controller

~~~ php
// src/Controller/DefaultController.php

/**
 * @Route("/autocomplete/{term}", options={"expose": true}, name="autocomplete-route")
 */
public function autocompleteIP(Request $request, ManagerRegistry $doctrine): JsonResponse
{
    $entityManager = $doctrine->getManager();
    $term = $request->get('term');
    
    /** @phpstan-ignore-next-line */
    $query = $entityManager->getRepository(MyEntity::class)->createQueryBuilder('m')
        ->andWhere('m.value LIKE :val')
        ->setParameter('val', '%'.$term.'%')
        ->getQuery()
    ;

    $result = $query->getResult(PDO::FETCH_ASSOC);

    return $this->json($result);
}
~~~
