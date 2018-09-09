<?php

namespace App\Twig;

use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use App\Entity\Projects;

class WbrExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface{

    /**
     *
     * @var Twig_Enviroment
     */
    private $enviroment;
    
    /**
     *
     * @var Doctrine
     */
    protected $Doctrine;
    
    public function initRuntime(\Twig_Environment $environment) {
        $this->enviroment = $environment;
    }
    
    public function __construct(Doctrine $Doctrine) {
        $this->Doctrine = $Doctrine;
    }
    
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('print_projects_menu', array($this, 'printProjectsMenu'), array('is_safe' => array('html')))
        );
    }
    
    public function printProjectsMenu()
    {
        $Repo = $this->Doctrine->getRepository(Projects::class);
        return $projectsList = $Repo->findAll();
        
        
        
        //return $this->enviroment->render('pages/index.html.twig', array('projectsList' => $projectsList));
    }
    
    
}
