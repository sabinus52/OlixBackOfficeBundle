<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Helper\DoctrineHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

/**
 * Restauration d'un dump de base de données.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
final class RestoreBaseCommand extends Command
{
    protected static $defaultName = 'app:database:restore';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Racine de l'emplacement des dumps.
     *
     * @var string
     */
    protected $pathRoot;

    /**
     * Constructeur.
     *
     * @param EntityManagerInterface $entityManager
     * @param string                 $pathRoot
     */
    public function __construct(EntityManagerInterface $entityManager, string $pathRoot)
    {
        parent::__construct();
        $this->pathRoot = $pathRoot;
        $this->entityManager = $entityManager;
    }

    /**
     * Configuration de la commande.
     */
    protected function configure(): void
    {
        $this
            ->addArgument('dump', InputArgument::OPTIONAL, 'Emplacement complet du dump à restaurer')
            ->addOption('dir', 'd', InputOption::VALUE_REQUIRED, 'Emplacement des derniers dumps disponibles')
            ->setDescription('Alias de la commande mysqlrestore')
            ->setHelp(<<<'EOT'
                La commande <info>%command.name%</info> réalise une restauration d'un dump.

                 <info>php %command.full_name% /var/backup/dump-base-YYYYMMDD-HHIISS</info>

                Si le dump est omis alors le dernier dump réalisé est utilisé depuis le dossier défini dans <info>--dir=[DIRECTORY_DUMP]</info>.
                (par défaut <comment>/tmp</comment> ou défini dans le paramètre <info>%env(BACKUP_PATH)%</info>

                EOT
            )
        ;
    }

    /**
     * Initialise la commande.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if (empty($this->pathRoot)) {
            $this->pathRoot = '/tmp';
        }
        if ($input->getOption('dir')) {
            $this->pathRoot = $input->getOption('dir');
        }
    }

    /**
     * Fait le dump.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $dumpFile = '';
        if ($input->getArgument('dump')) {
            $dumpFile = $input->getArgument('dump');
        } else {
            $dumpFile = $this->getLastDumpFile();
            if (null === $dumpFile) {
                $style->warning(sprintf('Aucun dump trouvé dans le dossier "%s"', $this->pathRoot));

                return Command::INVALID;
            }
        }

        if (!is_readable($dumpFile)) {
            $style->error(sprintf('Le dump "%s" n\'est pas accesssible.', $dumpFile));

            return Command::FAILURE;
        }

        // Confirmation de la restauration
        $style->caution('Toutes les données de la base vont être supprimés.');
        $style->info(sprintf('Dump qui sera restauré : %s', $dumpFile));
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Voulez vous continuer [y/N] ? ', false);
        if (!$helper->ask($input, $output, $question)) {
            $style->newLine();

            return Command::SUCCESS;
        }

        // Restauration
        $helper = new DoctrineHelper($this->entityManager);
        $return = $helper->restoreBase($dumpFile);

        if (0 === $return) {
            $style->success(sprintf('Le dump "%s" a été restauré avec succès', $dumpFile));
        } else {
            $style->error(sprintf('Echec de la restauration du dump "%s"', $dumpFile));
        }

        return ($return) ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Retourne le dernier dump réalisé.
     *
     * @return string|null
     */
    private function getLastDumpFile(): ?string
    {
        $helper = new DoctrineHelper($this->entityManager);
        $finder = new Finder();

        // Recherche les fichiers
        $finder->ignoreUnreadableDirs()->files()->in($this->pathRoot)->depth('== 0')->name(sprintf('dump-%s-*.sql', $helper->getDataBaseName()));
        $finder->sortByModifiedTime();
        if (!$finder->hasResults()) {
            return null;
        }

        // Prend le plus recent
        $files = iterator_to_array($finder);
        $dumpFile = end($files);
        if (!$dumpFile) {
            return null;
        }

        return $dumpFile->getRealPath();
    }
}
