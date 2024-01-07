<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Helper;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Classe d'aide sur les fonctions système.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class SystemHelper
{
    /**
     * @var Filesystem;
     */
    private $filesystem;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Purge les fichiers correspondant un masque d'un dossier.
     *
     * @param string $directory    Dossier de recherche
     * @param string $maskToFilter Masque des fichiers à chercher
     * @param int    $filesToKeep  Nombre de fichier à conserver
     */
    public function purgeFiles(string $directory, string $maskToFilter, int $filesToKeep = 10): void
    {
        $finder = new Finder();
        $files = $finder->ignoreUnreadableDirs()->files()->in($directory)->name($maskToFilter);

        // Trie les fichiers par date de modification (du plus récent au plus ancien)
        $files->sortByModifiedTime()->reverseSorting();

        // Supprime les fichiers excédant le nombre souhaité
        if ($files->count() > $filesToKeep) {
            $idx = 0;
            foreach ($files as $file) {
                ++$idx;
                // Conserve les plus recents
                if ($idx <= $filesToKeep) {
                    continue;
                }
                $this->filesystem->remove($file->getRealPath());
            }
        }
    }
}
