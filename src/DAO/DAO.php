<?php

namespace MicroCMS\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO 
{
    /**
      * Connexion à la base de données
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param \Doctrine\DBAL\Connection The database connection object
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * Autorise l'accès de la base de données à l'objet connexion 
     *
     * @return \Doctrine\DBAL\Connection The database connection object
     */
    protected function getDb() {
        return $this->db;
    }

   /**
     * Construit un objet du domaine à partir des données de la base de données 
     * Must be overridden by child classes.
     */
    protected abstract function buildDomainObject(array $row);
}
