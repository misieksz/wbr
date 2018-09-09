<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category extends AbstractTaxonomy {
   
    /**
     *
     * @ORM\OneToMany(
     *   targetEntity="Articles",
     *   mappedBy="category"
     * )
     */
    protected $articles;
}

