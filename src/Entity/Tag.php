<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class Tag extends AbstractTaxonomy {
    
    /**
     *
     * @ORM\ManyToMany(
     *   targetEntity="Articles",
     *   mappedBy="tags"
     * )
     */
    protected $articles;
}

