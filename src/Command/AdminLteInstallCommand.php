<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Command that places bundle web assets into a given directory.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Gábor Egyed <gabor.egyed@gmail.com>
 *
 * @final
 */
#[\Symfony\Component\Console\Attribute\AsCommand('assets:adminlte', 'Install assets AdminLTE under a public directory')]
class AdminLteInstallCommand extends Command
{
    protected const PATH_ASSETS_ADMINLTE = '/vendor/almasaeed2010/adminlte';

    private ?string $originDir = null;

    private ?string $targetDir = null;

    private int $exitCode = 0;

    /**
     * Constructeur.
     */
    public function __construct(private readonly Filesystem $filesystem, private readonly string $kernelProjectDir)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('target', InputArgument::OPTIONAL, 'The target directory', null),
            ])
            ->setHelp(<<<'EOT'
                The <info>%command.name%</info> command installs bundle assets AdminLTE into a given
                directory (e.g. the <comment>public</comment> directory).

                  <info>php %command.full_name% public</info>

                A "bundles" directory will be created inside the target directory and the
                "Resources/public" directory of each bundle will be copied into it.

                EOT
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $targetArg = rtrim($input->getArgument('target') ?? '', '/');
        if ('' === $targetArg) {
            $targetArg = $this->getPublicDirectory();
        }

        if (!is_dir($targetArg)) {
            $targetArg = $this->kernelProjectDir.'/'.$targetArg;

            if (!is_dir($targetArg)) {
                throw new InvalidArgumentException(sprintf('The target directory "%s" does not exist.', $targetArg));
            }
        }

        $this->originDir = $this->kernelProjectDir.self::PATH_ASSETS_ADMINLTE;
        $this->targetDir = $targetArg.'/bundles/adminlte';

        $inOut = new SymfonyStyle($input, $output);
        $inOut->newLine();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inOut = new SymfonyStyle($input, $output);
        $inOut->text('Installing assets as <info>hard copies</info>.');
        $inOut->newLine();

        $rows = [];

        try {
            $this->filesystem->remove($this->targetDir);
            $rows[] = $this->getRowMessage('Remove dir', $this->targetDir);
        } catch (\Exception $exception) {
            $this->exitCode = 1;
            $rows[] = $this->getRowMessage('Remove dir', $this->targetDir, $exception->getMessage());
        }

        $rows[] = $this->copyPlugins($this->originDir.'/plugins', $this->targetDir.'/plugins');
        $rows[] = $this->copyStyles($this->originDir.'/dist/css', $this->targetDir.'/css');
        $rows[] = $this->copyScripts($this->originDir.'/dist/js', $this->targetDir.'/js');

        $inOut->table(['', 'Action', 'Path', 'Error'], $rows);

        if (0 !== $this->exitCode) {
            $inOut->error('Some errors occurred while installing assets.');
        } else {
            $inOut->success('All assets were successfully installed.');
        }

        return $this->exitCode;
    }

    /**
     * Copie des plugins.
     *
     * @return array<mixed>
     */
    private function copyPlugins(string $originDir, string $targetDir): array
    {
        try {
            $this->filesystem->mkdir($targetDir, 0777);
            $this->filesystem->mirror($originDir, $targetDir, Finder::create()->ignoreDotFiles(false)->in($originDir));

            return $this->getRowMessage('Copy plugins', $targetDir);
        } catch (\Exception $exception) {
            $this->exitCode = 1;

            return $this->getRowMessage('Copy plugins', $targetDir, $exception->getMessage());
        }
    }

    /**
     * Copie des styles.
     *
     * @return array<mixed>
     */
    private function copyStyles(string $originDir, string $targetDir): array
    {
        try {
            $this->filesystem->mkdir($targetDir, 0777);
            $this->filesystem->mirror($originDir, $targetDir, Finder::create()->depth('== 0')->ignoreDotFiles(false)->name('admin*.css*')->in($originDir));

            return $this->getRowMessage('Copy styles', $targetDir);
        } catch (\Exception $exception) {
            $this->exitCode = 1;

            return $this->getRowMessage('Copy styles', $targetDir, $exception->getMessage());
        }
    }

    /**
     * Copie des scripts.
     *
     * @return array<mixed>
     */
    private function copyScripts(string $originDir, string $targetDir): array
    {
        try {
            $this->filesystem->mkdir($targetDir, 0777);
            $this->filesystem->mirror($originDir, $targetDir, Finder::create()->depth('== 0')->ignoreDotFiles(false)->name('admin*.js*')->in($originDir));

            return $this->getRowMessage('Copy scripts', $targetDir);
        } catch (\Exception $exception) {
            $this->exitCode = 1;

            return $this->getRowMessage('Copy scripts', $targetDir, $exception->getMessage());
        }
    }

    /**
     * Retourne le chemin absolu du dossier web public.
     */
    private function getPublicDirectory(): string
    {
        $defaultPublicDir = 'public';

        if (empty($this->kernelProjectDir)) {
            return $defaultPublicDir;
        }

        $composerFilePath = $this->kernelProjectDir.'/composer.json';

        if (!file_exists($composerFilePath)) {
            return $defaultPublicDir;
        }

        $composerConfig = json_decode((string) file_get_contents($composerFilePath), true);

        return $composerConfig['extra']['public-dir'] ?? $defaultPublicDir;
    }

    /**
     * Retourne le tableau de message.
     *
     * @return array<string>
     */
    private function getRowMessage(string $message, string $dir, string $error = null): array
    {
        if (null === $error) {
            return [sprintf('<fg=green;options=bold>%s</>', '\\' === \DIRECTORY_SEPARATOR ? 'OK' : "\xE2\x9C\x94" /* HEAVY CHECK MARK (U+2714) */), $message, $dir, ''];
        }

        return [sprintf('<fg=red;options=bold>%s</>', '\\' === \DIRECTORY_SEPARATOR ? 'ERROR' : "\xE2\x9C\x98" /* HEAVY BALLOT X (U+2718) */), $message, $dir, $error];
    }
}
