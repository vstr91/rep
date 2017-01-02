<?php

namespace Rep\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\LogEntryRepository")
 */
class LogEntry extends \Gedmo\Loggable\Entity\LogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
}
