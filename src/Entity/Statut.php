<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="statut")
 */
class Statut 
{
    /**
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     *
     * @ORM\Column(type="text")
     */
    private $context;
    
    public function getId() {
        return $this->id;
    }

    public function getContext() {
        return $this->context;
    }

    public function setContext($context) {
        $this->context = $context;
        return $this;
    }


}