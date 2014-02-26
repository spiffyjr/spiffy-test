<?php

namespace SpiffyTest\Framework;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class DoctrineOrmTestCase extends TestCase
{
    /**
     * @var bool
     */
    protected $hasDb = false;

    /**
     * @param null|EntityManager $em
     */
    public function createDb(EntityManager $em = null)
    {
        if ($this->hasDb) {
            return;
        }
        if (null === $em) {
            $em = $this->getEntityManager();
        }
        $tool = new SchemaTool($em);
        $tool->updateSchema($em->getMetadataFactory()->getAllMetadata());
        $this->hasDb = true;
    }

    /**
     * @param null|EntityManager $em
     */
    public function dropDb(EntityManager $em = null)
    {
        if (!$this->hasDb) {
            return;
        }
        if (null == $em) {
            $em = $this->getEntityManager();
        }
        $tool = new SchemaTool($em);
        $tool->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $em->clear();
        $this->hasDb = false;
    }

    /**
     * Gets the default (orm_default) entity manager instance.
     */
    public function getEntityManager()
    {
        return $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
    }
}