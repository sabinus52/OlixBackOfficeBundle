<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Controller;

use Olix\BackOfficeBundle\Service\AutoCompleteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur des différentes pages.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
class DefaultController extends AbstractController
{
    /**
     * Autocompletion des Select2 en mode AJAX.
     */
    #[Route(path: '/olix/autocomplete/select2', name: 'olix_autocomplete_select2')]
    public function getSearchAutoCompleteSelect2(Request $request, AutoCompleteService $autoComplete): JsonResponse
    {
        // Récupère la classe défini dans la widget et passé en paramètre
        $classForm = (string) $request->get('class'); /** @phpstan-ignore cast.string */
        $results = $autoComplete->getResults($classForm, $request);

        return $this->json($results);
    }
}
