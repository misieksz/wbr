<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Entity\Articles;
use App\Form\ArticleType;
use App\Form\RegisterUserType;
use App\Exception\UserException;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Form\UserSetingsType;
use App\Form\ChangePasswordType;
use App\Libs\FileUploader;
use Symfony\Component\Form\FormError;
use App\Libs\ThumbnailsMaker;
use App\Entity\Projects;
use App\Form\ProjectsType;

class AdminController extends Controller {

    /**
     * @Route("/", name="dashboard")
     * 
     * @Template()
     */
    public function dashboard() {
        return array();
    }

     /**
     * @Route("/projects/{page}", 
      * name="admin_projects",
      * defaults={"page" = 1},
      * requirements={"page" = "\d+"})
     * 
     * @Template()
     */
    public function admin_projects($page) {
        $delToken = 'delTokId%d';

        $projects =  $this->getDoctrine()->getRepository(Projects::class)
                ->findAll();
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($projects, $page, 10);


        return array(
            'pagination' => $pagination,
            'delToken' => $delToken
        );
    }
    
      /**
     * @Route("/projects/add", name="add_project")
     * @Template 
     */
    public function addProject(Request $request) {
        
        $Project = new Projects($this->getParameter('projects_images'));
        $formProjects = $this->createForm(ProjectsType::class, $Project);
        
        $formProjects->handleRequest($request);
        
        
       
           if ($request->isMethod('POST')) {
            $setSlug = $Project->setSlug($Project->getTitle());
            $slug = $Project->getSlug();

            $checkSlug = $this->getDoctrine()->getRepository(Projects::class)->checkSlug($slug);


            if (0 !== count($checkSlug)) {
                $error = new FormError('Zmień tytuł artykułu!');
                $formProjects->addError($error);
            }
        }

         if($formProjects->isSubmitted() && $formProjects->isValid())
        {
            if (null !== $Project->getFiles()) {

                foreach($Project->getFiles() as $file)
                { 
                
                $FileUploader = new FileUploader();
                $FileUploader->setFile($file);
                $NewFileName = $FileUploader->generateFileName();

                $SaveFile = $FileUploader->saveFile($this->getParameter('projects_images'), $NewFileName);
                $tmpName = $SaveFile;
                $newName = sha1($Project->getTitle()) . $tmpName;


                new ThumbnailsMaker($this->getParameter('projects_images') . $tmpName, 300, 300, $this->getParameter('projects_min_images') . $newName);
                new ThumbnailsMaker($this->getParameter('projects_images') . $tmpName, 900, 900, $this->getParameter('projects_images') . $newName);

                unlink($this->getParameter('projects_images') . $tmpName);
                $Project->addImages($newName);
            }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($Project);
            $em->flush();
            $this->get('session')->set('editMsg', 'Nowy projekt został utworzony!');
            return $this->redirect($this->generateUrl('admin_projects'));
        }
        
        return array(
            'formProjects' => $formProjects->createView(),
            'dump' => isset($new) ? $new : null
        );
    }
    
        /**
     * 
     * @Route("/projects/delete/{proId}/{delToken}", name="project_delete")
     * 
     */
    public function deleteProjects($proId, AuthorizationCheckerInterface $autoChecker, $delToken) {
       
        if (false === $autoChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Brak uprawnień do obsługi tej strony!');
        }

        $path = $this->getParameter('projects_images');
        $minPath = $this->getParameter('projects_min_images');

        $repo = $this->getDoctrine()
                ->getRepository(Projects::class);
        $project = $repo ->find($proId);

        if (null == $project) {
            throw $this->createNotFoundException('Nie znaleziono projektu do usunięcia!');
        }

        $validToken = sprintf('delTokId%d', $proId);

        if (!$this->isCsrfTokenValid($validToken, $delToken)) {
            throw new AccessDeniedException('Błędny token akcji!');
        }
        
        if(null !== $project->getImages()) {
            
            foreach($project->getImages() as $image)
            {
                unlink($path . $image);
                unlink($minPath . $image);
            }
            
                
               }

        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'status' => 'ok',
            'message' => 'Projekt został usunięty!'
        ));
    }
 /**
     * @Route("/projects/edit/{id}", name="edit_project")
     * 
     * @Template
     */
    public function edit_project(Request $Request, $id) {
        
        $path = $this->getParameter('projects_images');
        $minPath = $this->getParameter('projects_min_images');
        

        $repo = $this->getDoctrine()->getRepository(Projects::class);
        $Project = $repo->find($id);

        if (null === $Project) {
            throw $this->createNotFoundException('Nie znaleziono projektu do edycji!');
        }
        
        $data = $Project->getTitle();
        $images = $Project->getImages();

        $Session = $this->get('session');
        $form = $this->createForm(ProjectsType::class, $Project);



        $form->handleRequest($Request);
        
        
        if($Request->isMethod('POST')){
          
           
            if($Project->getTitle() !== $data) {
            
                
            $setSlug = $Project->setSlug($Project->getTitle());
            $slug = $Project->getSlug();

            $checkSlug = $this->getDoctrine()->getRepository(Projects::class)->checkSlug($slug);


            if (0 !== count($checkSlug)) {
                $error = new FormError('Zmień tytuł projektu!');
                $form->addError($error);
            }
            }
        }
    
        if ($form->isSubmitted() && $form->isValid()) {

        
             if(null !== $Project->getImages()) {
            
            foreach($Project->getImages() as $image)
            {
                unlink($path . $image);
                unlink($minPath . $image);
            }
            
                
               }
            
                if (null !== $Project->getFiles()) {

                foreach($Project->getFiles() as $file)
                { 
                
                $FileUploader = new FileUploader();
                $FileUploader->setFile($file);
                $NewFileName = $FileUploader->generateFileName();

                $SaveFile = $FileUploader->saveFile($this->getParameter('projects_images'), $NewFileName);
                $tmpName = $SaveFile;
                $newName = sha1($Project->getTitle()) . $tmpName;


                new ThumbnailsMaker($this->getParameter('projects_images') . $tmpName, 300, 300, $this->getParameter('projects_min_images') . $newName);
                new ThumbnailsMaker($this->getParameter('projects_images') . $tmpName, 900, 900, $this->getParameter('projects_images') . $newName);

                unlink($this->getParameter('projects_images') . $tmpName);
                $Project->addImages($newName);
            }
            }
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($Project);
            $em->flush();


            $Session->set('editMsg', 'Zmiany w projekcie zostały zatwierdzone.');
            return $this->redirect($this->generateUrl('admin_projects'));
        }




        return array(
            'form' => $form->createView(),
            'data' => isset($data) ? $data : null
        );
    }


    
    /**
     * @Route("/articles/{status}/{page}", name="admin_articles",
     *      defaults={"status"="all", "page"=1},
     *      requirements={"page"="\d+", "status"="all|published|unpublished"}
     * )
     * 
     * @Template()
     */
    public function admin_articles($status, $page) {
        
        
        
        $delToken = 'delTokId%d';

        $publishedStatus = array(
            'Wszystkie' => 'all',
            'Opublikowane' => 'published',
            'Nieopublikowane' => 'unpublished'
        );
        
        $params = array(
            'status' => $status
        );
        
        $adminArts = $this->getDoctrine()->getRepository(Articles::class)
                ->getQueryBuilder($params);
       
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($adminArts, $page, 10);
        
        $statistics = $this->getDoctrine()->getRepository(Articles::class)
                ->getStatistics();

        return array(
            'pagination' => $pagination,
            'delToken' => $delToken,
            'publishedStatus' => $publishedStatus,
            'status' => $status,
            'statistics'=> $statistics
        );
    }

    /**
     * 
     * @Route("/articles/delete/{artId}/{delToken}", name="art_delete")
     * 
     */
    public function deleteArticles($artId, AuthorizationCheckerInterface $autoChecker, $delToken) {
       
        if (false === $autoChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Brak uprawnień do obsługi tej strony!');
        }

        $path = $this->getParameter('upload_directory');
        $minPath = $this->getParameter('min_upload_directory');

        $repo = $this->getDoctrine()
                ->getRepository(Articles::class);
        $art = $repo ->find($artId);

        if (null == $art) {
            throw $this->createNotFoundException('Nie znaleziono postu do usunięcia!');
        }

        $validToken = sprintf('delTokId%d', $artId);

        if (!$this->isCsrfTokenValid($validToken, $delToken)) {
            throw new AccessDeniedException('Błędny token akcji!');
        }
        
        if(null !== $art->getImage()) {
                unlink($path . $art->getImage());
                unlink($minPath . $art->getImage());
               }

        $em = $this->getDoctrine()->getManager();
        $em->remove($art);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'status' => 'ok',
            'message' => 'Artykuł został usunięty!'
        ));
    }
    
        /**
     * 
     * @Route("/users/delete/{userId}/{delToken}", name="users_delete")
     * 
     */
    public function deleteUsers($userId, AuthorizationCheckerInterface $autoChecker, $delToken) {
       
        if (false === $autoChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Brak uprawnień do obsługi tej strony!');
        }


        $repo = $this->getDoctrine()
                ->getRepository(User::class);
        $user = $repo ->find($userId);

        if (null == $user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika, którego chcesz usunąć!');
        }

        $validToken = sprintf('delTokId%d', $userId);

        if (!$this->isCsrfTokenValid($validToken, $delToken)) {
            throw new AccessDeniedException('Błędny token akcji!');
        }
        

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'status' => 'ok',
            'message' => 'Użytkownik został usunięty!'
        ));
    }

    /**
     * @Route("/articles/add", name="add_article")
     * 
     * @Template
     */
    public function add_article(Request $request) {
        $path = $this->getParameter('upload_directory');
        $Article = new Articles();

        $Session = $this->get('session');

        $formArticle = $this->createForm(ArticleType::class, $Article);

        $formArticle->handleRequest($request);

        if ($request->isMethod('POST')) {
            $setSlug = $Article->setSlug($Article->getTitle());
            $slug = $Article->getSlug();

            $checkSlug = $this->getDoctrine()->getRepository(Articles::class)->checkSlug($slug);


            if (0 !== count($checkSlug)) {
                $error = new FormError('Zmień tytuł artykułu!');
                $formArticle->addError($error);
            }
        }

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {

            $Article->setAuthor($this->getUser());

            if (null !== $Article->getThumbnail()) {

                $FileUploader = new FileUploader();
                $FileUploader->setFile($Article->getThumbnail());
                $NewFileName = $FileUploader->generateFileName();

                $SaveFile = $FileUploader->saveFile($this->getParameter('upload_directory'), $NewFileName);
                $tmpName = $SaveFile;
                $newName = sha1($Article->getTitle()) . $tmpName;


                new ThumbnailsMaker($this->getParameter('upload_directory') . $tmpName, 90, 90, $this->getParameter('min_upload_directory') . $newName);
                new ThumbnailsMaker($this->getParameter('upload_directory') . $tmpName, 900, 900, $this->getParameter('upload_directory') . $newName);

                unlink($path . $tmpName);
                $Article->setImage($newName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();
            $Session->set('addArt', 'Artykuł został dodany!');
            return $this->redirect($this->generateUrl('admin_articles'));
        }

        return array(
            'formArticle' => $formArticle->createView()
        );
    }

    /**
     * @Route("/articles/edit/{id}", name="edit_article")
     * 
     * @Template
     */
    public function edit_article(Request $Request, $id) {
        $path = $this->getParameter('upload_directory');
        $minPath = $this->getParameter('min_upload_directory');



        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $Article = $repo->find($id);

        if (null === $Article) {
            throw $this->createNotFoundException('Nie znaleziono artykułu do edycji!');
        }
        
        $data = $Article->getTitle();
        $image = $Article->getImage();

        $Session = $this->get('session');
        $form = $this->createForm(ArticleType::class, $Article);



        $form->handleRequest($Request);
        
        
        if($Request->isMethod('POST')){
          
           
            if($Article->getTitle() !== $data) {
            
                
            $setSlug = $Article->setSlug($Article->getTitle());
            $slug = $Article->getSlug();

            $checkSlug = $this->getDoctrine()->getRepository(Articles::class)->checkSlug($slug);


            if (0 !== count($checkSlug)) {
                $error = new FormError('Zmień tytuł artykułu!');
                $form->addError($error);
            }
            }
        }
        
        if ($form->isSubmitted() && $form->isValid()) {

            if (null !== $Article->getThumbnail()) {

               if(null !== $image) {
                unlink($path . $Article->getImage());
                unlink($minPath . $Article->getImage());
               }
                $FileUploader = new FileUploader();
                $FileUploader->setFile($Article->getThumbnail());
                $NewFileName = $FileUploader->generateFileName();

                $SaveFile = $FileUploader->saveFile($path, $NewFileName);
                $tmpName = $SaveFile;
                $newName = sha1($Article->getTitle()) . $tmpName;


                new ThumbnailsMaker($path . $tmpName, 90, 90, $minPath . $newName);
                new ThumbnailsMaker($path . $tmpName, 900, 900, $path . $newName);

                unlink($path . $tmpName);
                $Article->setImage($newName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();


            $Session->set('editMsg', 'Zmiany w artykule zostały zatwierdzone.');
            return $this->redirect($this->generateUrl('admin_articles'));
        }




        return array(
            'form' => $form->createView(),
            'data' => isset($data) ? $data : null
        );
    }
    
    /**
     * @Route("users", name="admin_users")
     * 
     * @Template
     */
    public function admin_users()
    {
        $delToken = 'delTokId%d';
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        
        return array(
            'users' => $users,
            'delToken' => $delToken
        );
    }
    

    /**
     * 
     * @Route("/register-user", name="register_user")
     * 
     * 
     * @Template
     */
    public function registerUserAction(Request $Request) {
        $User = new User();
        $registerForm = $this->createForm(RegisterUserType::class, $User);


        $registerForm->handleRequest($Request);


        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            try {
                $userManager = $this->get('user_manager');
                $userManager->registerUser($User);
                $this->get('session')->getFlashBag()->add('success', 'Konto zostało utworzone, na Twój adres email został wysłany link akrywacyjny!');
                return $this->redirect($this->generateUrl('register_user'));
            } catch (UserException $ex) {
                $this->get('session')->getFlashBag()->add('danger', $ex->getMessage());
            }
        }

        return array(
            'registerForm' => $registerForm->createView()
        );
    }

    /**
     * @Route("/activate-account/{actionToken}", name="activate_account")
     * 
     */
    public function activateAccount($actionToken) {
        try {
            $userManager = $this->get('user_manager');
            $userManager->activateAccount($actionToken);
            $this->get('session')->set('success', 'Twoje konto zostało aktywowane!');

            return $this->redirect($this->generateUrl('login'));
        } catch (UserException $ex) {
            $this->get('session')->getFlashBag('success', $ex->getMessage());
        }
    }

    /**
     * @Route("/user-setings", name="user_setings")
     * @Template
     */
    public function userSetings(Request $Request) {


        $User = $this->getUser();

        $userSetingsForm = $this->createForm(UserSetingsType::class, $User);
        $changePasswordForm = $this->createForm(ChangePasswordType::class, $User);

        if ($Request->request->has('user_setings')) {

            $userSetingsForm->handleRequest($Request);

            if ($userSetingsForm->isSubmitted() && $userSetingsForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($User);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Nazwa użytkownika została zmieniona!');

                return $this->redirect($this->generateUrl('user_setings'));
            }
        }

        if ($Request->request->has('change_password')) {
            $changePasswordForm->handleRequest($Request);

            if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
                try {
                    $userManager = $this->get('user_manager');
                    $userManager->changePassword($User);
                    $this->get('session')->getFlashBag()->add('success', 'Hasło zostało poprawnie zmienione!');
                } catch (UserException $ex) {
                    $this->get('session')->getFlashBag()->add('danger', $ex->getMessage());
                }
            }
        }

        return array(
            'userSetingsForm' => $userSetingsForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView()
        );
    }
    
    /**
     * @Route("/edit-user/{id}", name="edit_users")
     * 
     * @Template
     */
    public function edit_users($id, Request $request)
    {
        $repo = $this->getDoctrine()
                ->getRepository(User::class);
        $user = $repo ->find($id);
        
        if(null === $user)
        {
            throw $this->createNotFoundException('Nie znaleziono takiego użytkownika!');
        }
        
        
       
        $userForm = $this->createForm(\App\Form\UserType::class, $user);
        
        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            $this->get('session')->set('editMsg', 'Dane użytkownika zostały zmienione!');
            return $this->redirect($this->generateUrl('admin_users'));
        }
        
        return array(
            'userForm' => $userForm->createView()
        );
    }


    /**
     * @Route("/param/{id}")
     */
    public function param($id) {
       $repo = $this->getDoctrine()->getRepository(Projects::class);
        $Project = $repo->find($id);
        $data = null;

        if(!empty($Project->getImages()))
        {
            $data = 0;
        } else {
            $data = 1;
        }

        return new Response($data);
    }

}
