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
#[\Symfony\Component\Console\Attribute\AsCommand('app:database:dump', 'Alias de la commande mysqldump')]
final class DumpBaseCommand extends Command
{
    /**
     * Nombre de fichiers à conserver.
     */
    protected int $filesToKeep;

    /**
     * Constructeur.
     *
     * @param string $pathRootBackup Racine de l'emplacement des dumps
     */
    public function __construct(protected EntityManagerInterface $entityManager, protected string $pathRootBackup)
    {
        parent::__construct();
    }

    /**
     * Configuration de la commande.
     */
    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::OPTIONAL, 'Emplacement du fichier de dump de la base de données')
            ->addOption('purge', 'p', InputOption::VALUE_REQUIRED, 'Nombre de fichier à purger')
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
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if (empty($this->pathRootBackup)) {
            $this->pathRootBackup = '/tmp';
        }

        if ($input->getArgument('path')) {
            $this->pathRootBackup = $input->getArgument('path');
        }

        if ($input->getOption('purge')) {
            $this->filesToKeep = (int) $input->getOption('purge');
        }
    }

    /**
     * Fait le dump.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        // Test du dossier
        if (!file_exists($this->pathRootBackup)) {
            $style->error(sprintf('Le dossier "%s" n\'existe pas.', $this->pathRootBackup));

            return Command::FAILURE;
        }

        if (!is_writable($this->pathRootBackup)) {
            $style->error(sprintf('Le dossier "%s" n\'est pas accesssible.', $this->pathRootBackup));

            return Command::FAILURE;
        }

        // Sauvegarde
        $helper = new DoctrineHelper($this->entityManager);
        $return = $helper->dumpBase($this->pathRootBackup);

        // Purge
        if (!empty($this->filesToKeep)) {
            $helper = new SystemHelper();
            $helper->purgeFiles($this->pathRootBackup, 'dump-*.sql', $this->filesToKeep);
        }

        if (0 === $return[0]) {
            $style->success(sprintf('Le dump "%s" de la base a été sauvegardé avec succès', $return[1]));
        } else {
            $style->error(sprintf('Echec du dump "%s"', $return[1]));
        }

        return ($return[0]) ? Command::SUCCESS : Command::FAILURE;
    }
}
