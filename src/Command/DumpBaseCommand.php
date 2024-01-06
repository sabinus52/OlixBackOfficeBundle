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
use Olix\BackOfficeBundle\Helper\SystemHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Sauvegarde de la base de données.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
final class DumpBaseCommand extends Command
{
    protected static $defaultName = 'app:database:dump';

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
     * Nombre de fichiers à conserver.
     *
     * @var int
     */
    protected $filesToKeep;

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
            ->addArgument('path', InputArgument::OPTIONAL, 'Emplacement du fichier de dump de la base de données')
            ->addOption('purge', 'p', InputOption::VALUE_REQUIRED, 'Nombre de fichier à purger')
            ->setDescription('Alias de la commande mysqldump')
            ->setHelp(<<<'EOT'
                La commande <info>%command.name%</info> réalise un dump dans le chemin de son choix.
                (par défaut <comment>/tmp</comment> ou défini dans le paramètre <info>%env(BACKUP_PATH)%</info>

                 <info>php %command.full_name% /var/backup</info>

                Le dump de sortie est au format <comment>dump-[BASENAME]-YYYYMMDD-HHIISS.sql</comment>.

                On peut purger les anciens dump avec le paramètre <info>--purge=[NUMBER_FILES_TO_KEEP]</info>.

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
        if ($input->getArgument('path')) {
            $this->pathRoot = $input->getArgument('path');
        }

        if ($input->getOption('purge')) {
            $this->filesToKeep = (int) $input->getOption('purge');
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

        // Test du dossier
        if (!file_exists($this->pathRoot)) {
            $style->error(sprintf('Le dossier "%s" n\'existe pas.', $this->pathRoot));

            return Command::FAILURE;
        }
        if (!is_writable($this->pathRoot)) {
            $style->error(sprintf('Le dossier "%s" n\'est pas accesssible.', $this->pathRoot));

            return Command::FAILURE;
        }

        // Sauvegarde
        $helper = new DoctrineHelper($this->entityManager);
        $return = $helper->dumpBase($this->pathRoot);

        // Purge
        if (!empty($this->filesToKeep)) {
            $helper = new SystemHelper();
            $helper->purgeFiles($this->pathRoot, 'dump-*.sql', $this->filesToKeep);
        }

        if (0 === $return[0]) {
            $style->success(sprintf('Le dump "%s" de la base a été sauvegardé avec succès', $return[1]));
        } else {
            $style->error(sprintf('Echec du dump "%s"', $return[1]));
        }

        return ($return) ? Command::SUCCESS : Command::FAILURE;
    }
}
