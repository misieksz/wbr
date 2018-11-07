<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ContactType;
use App\Entity\Contact;
use App\Entity\Articles;
use App\Entity\User;
use App\Exception\UserException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType;
use App\Form\RememberPasswordType;
use App\Entity\Projects;
use App\Entity\Statut;

class PagesController extends Controller
{
    const DEFAULT_IMAGE = "wbr.jpg";


    /**
     * 
     *  @Route("/", name="home"
     * )
     * 
     * @Template
     * 
     */
    public function index()
    {
         $Repo = $this->getDoctrine()->getRepository(Articles::class);
        $news = $Repo->newestQuery();
        
        
        
        return array(
            'news' => $news
        );
    }
    
    /**
     * @Route("/statut", name="statut")
     * 
     * @Template
     */
     public function statut()
     {
         $Repo = $this->getDoctrine()->getRepository(Statut::class)->findAll();
         
         return array(
             'statut' => $Repo
         );
     }
        
     /**
     * 
     * @Route("/artykuly/{page}", 
     * name="articles",
      * defaults={"page" = 1},
      * requirements={"page" = "\d+"}
     * 
     * )
     * 
     * 
     * @Template()
     */
    public function articles($page)
    {
        $Repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $Repo->getQueryBuilder(array(
            'status' => 'published',
            'orderDir' => 'DESC',
            'orderBy' => 'a.publishedDate'
        ));
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($articles, $page, 3);
        
        
        return array(
            'pagination' => $pagination
        );
    }
    
     /**
     * @Route("/projekty/{slug}", name="show_projects",
     * 
     * )
     * 
     * @Template()
     */
    public function projects($slug)
    {
        $Repo = $this->getDoctrine()->getRepository(Projects::class);
        $projects = $Repo->findOneBy(array('slug' => $slug));
        
        if(NULL == $projects)
        {
            throw $this->createNotFoundException('Nie znaleziono projektu!');
        }
         
        
        return array(
            'projects' => $projects,
        );
    }
    
     /**
     * 
     * @Route("/kategoria/{slug}/{page}", 
     * name="categoryArts",
      * defaults={"page" = 1},
      * requirements={"page" = "\d+"}
     * 
     * )
     * 
     * 
     * @Template("pages/articles.html.twig")
     */
    public function categoryArts($slug, $page)
    {
       
        $Repo = $this->getDoctrine()->getRepository(Articles::class);
        $category = $Repo->getQueryBuilder(array(
            'status' => 'published',
            'orderBy' => 'a.publishedDate',
            'categorySlug' => $slug
        ));
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($category, $page, 3);
        
        return array(
            'pagination' => $pagination
        );
    }
    
    /**
     * @Route("/kontakt", name="contact")
     * 
     * @Template()
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
       $Contact = new Contact();
       $form = $this->createForm(ContactType::class, $Contact);
       
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid())
       {
           
           $Contact->sendEmail($mailer);
           $Session = $this->get('session');
           $Session->set('msgConfirm', 'Dziękujemy za wysłanie wiadomości!');
           
           return $this->redirectToRoute('contact');
       }
       
       
       return array('form' => $form->createView());
         
    }
    
    /**
     * @Route("/login", name="login")
     * 
     * @Template()
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
    $error = $authUtils->getLastAuthenticationError();
    
    
    $lastUsername = $authUtils->getLastUsername();
    
    $login_form = $this->createForm(LoginType::class);

    return array(
        'last_username' => $lastUsername,
        'error' => $error,
        'login_form' => $login_form->createView()
    );
    }   
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return array();
    }
    
    /**
     * @Route("/remember-password", name="remember_password")
     * @Template
     */
    public function remember_password(Request $Request)
    {
        $rememberForm = $this->createForm(RememberPasswordType::class);
        
            $rememberForm->handleRequest($Request);
            if($rememberForm->isSubmitted() && $rememberForm->isValid())
            {
                try{
                $userEmail = $rememberForm->get('email')->getData();
                $userManager = $this->get('user_manager');
                $userManager->sendResetPasswordLink($userEmail);
                
                $this->get('session')->getFlashBag()->add('success', 'Link resetujący hasło został wysłany na Twój adres email!');
                return $this->redirect($this->generateUrl('remember_password'));
                
                } catch  (UserException $ex) {
                 // $error = new FormError($ex->getMessage());
                  //$rememberForm->addError($error);
                }
                
                
               
                
            }
        
        return array(
            'rememberForm' => $rememberForm->createView()
        );
    }
    
    /**
     * @Route("/reset-password/{actionToken}", name="user_resetPassword")
     */
     public function resetPasswordAction($actionToken)
     {
         try{
         $userManager = $this->get('user_manager');
         $userManager->resetPassword($actionToken);
         $this->get('session')->getFlashBag('success', 'Nowe hasło zostało wysłane na Twój adres email!');
         } catch (Exception $ex) {
             $this->get('session')->getFlashBag('danger', $ex->getMessage());
         }
         
         return $this->redirect($this->generateUrl('login'));
         
     }
    
}
