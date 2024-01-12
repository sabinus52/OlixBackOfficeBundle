<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Aide sur le fonctions de la base de données.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class DoctrineHelper
{
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * Constructeur.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * Purge la table.
     *
     * @return string|null
     */
    public function truncate(string $entityClass): ?string
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $table = $this->getTableNameForEntity($entityClass);

        try {
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0;');
            $connection->executeStatement($platform->getTruncateTableSQL($table, false /* whether to cascade */));
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return null;
    }

    /**
     * Retourne le nom de la table d'une entité.
     *
     * @return string
     */
    public function getTableNameForEntity(string $entityClass): string
    {
        $metadata = $this->entityManager->getClassMetadata($entityClass);

        return $metadata->getTableName();
    }

    /**
     * Retourne le nom de la base de données.
     *
     * @return string
     */
    public function getDataBaseName(): string
    {
        return $this->entityManager->getConnection()->getDatabase();
    }

    /**
     * Fait un dump de la base de données.
     *
     * @param string $pathRoot Chemin complet du dump
     *
     * @return array<mixed>
     */
    public function dumpBase(string $pathRoot): array
    {
        $connection = $this->entityManager->getConnection();
        /** @var array<mixed> $params */
        $params = $connection->getParams();

        if ('pdo_mysql' !== $params['driver']) {
            throw new \LogicException('Only MySQL database is supported');
        }

        $dumpfile = sprintf('%s/dump-%s-%s.sql', $pathRoot, $connection->getDatabase(), date('Y-m-d-His'));

        $host = escapeshellarg((string) $params['host']);
        $port = escapeshellarg((string) $params['port']);
        $username = escapeshellarg((string) $params['user']);
        $password = escapeshellarg((string) $params['password']);
        $database = escapeshellarg($connection->getDatabase());

        $cmd = "mysqldump -h {$host} -P {$port} -u {$username} -p{$password} {$database} > '{$dumpfile}'";
        passthru($cmd, $return);

        return [$return, $dumpfile];
    }

    /**
     * Restauration d'un dump.
     *
     * @return int
     */
    public function restoreBase(string $dumpFile): int
    {
        $connection = $this->entityManager->getConnection();
        /** @var array<mixed> $params */
        $params = $connection->getParams();

        if ('pdo_mysql' !== $params['driver']) {
            throw new \LogicException('Only MySQL database is supported');
        }

        $host = escapeshellarg((string) $params['host']);
        $port = escapeshellarg((string) $params['port']);
        $username = escapeshellarg((string) $params['user']);
        $password = escapeshellarg((string) $params['password']);
        $database = escapeshellarg($connection->getDatabase());

        $cmd = "mysql -h {$host} -P {$port} -u {$username} -p{$password} {$database} < '{$dumpFile}'";
        passthru($cmd, $return);

        return $return;
    }
}
