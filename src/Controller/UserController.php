<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\BrowserKit\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $user
        ]);
    }

    
    /**
     * @Route("/user/{id}/edit" , name="user_edit")
    */
    
    public function form(user $user ,Request $request,  ManagerRegistry $manager, UserPasswordEncoderInterface $encoder ) {

        if(!$user){
        $user = new user();
        }

        $form = $this->createFormBuilder($user)
        ->add('name' )
        ->add('email' )
        ->add('role' )
        ->add('password')
        ->add('confirm_password')
    
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();

            return $this->redirectToRoute('user' ,['id' => $user->getId()]);

        }


        return $this->render('User/index.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

      /**
     * @Route("/user/remove/{id}" , name="user_remove")
     */

    public function remove(User $user, ManagerRegistry $manager)
    {
        
        if (!$user)
        {
            throw $this->createNotFoundException('No user found');
        }
        
       
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        return $this->render("User/deleted.html.twig");

    }
}
