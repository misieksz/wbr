<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Articles;

class ArticlesController extends Controller
{
    /**
     * @Route("/artykul/{slug}", name="article",
     * 
     * )
     * 
     * @Template()
     */
    public function showArt($slug)
    {
        $Repo = $this->getDoctrine()->getRepository(Articles::class);
        $article = $Repo->findOneBy(array('slug' => $slug));
        
        if(NULL == $article)
        {
            throw $this->createNotFoundException('Nie znaleziono artykułu!');
        }
        
        return array(
            'article' => $article
        );
    }
}
