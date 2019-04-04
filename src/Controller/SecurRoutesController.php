<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditProfileType;
use App\Form\InscriptionType;
use App\Repository\UtilisateurRepository;
use App\Repository\ChienRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zend\Code\Scanner\Util;

class SecurRoutesController extends Controller
{
    /**
     * @Route("/", name="secur_inscription", name="home")
     */
    public function secur_inscription_route(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        //nouvel objet utilisateur vide
        $utilisateur = new Utilisateur();

        //Creation formulaire, relié à l'objet utilisateur
        $form = $this->createForm(InscriptionType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Si le formulaire est valide, alors on crée l'utilisateur avec ses infos
            $hash_password = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($hash_password);
            $manager->persist($utilisateur);
            $manager->flush();

            //Flash message indication
            $this->addFlash('info', 'Vous pouvez maintenant essayer de vous connecter à votre compte.');

            return $this->redirectToRoute('secur_connexion');
        }

        //Redirection si l'utilisateur est déjà connecté.
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('dashboard');
        }

        //Affichage de la page d'accueil si l'utilisateur n'est pas connecté
        return $this->render('secur_routes/index.html.twig', [
            'controller_name' => 'SecurRoutesController',
            'form_enreg' => $form->createView()
        ]);
    }


    /**
     * @Route("/connexion", name="secur_connexion")
     */
    public function secur_connexion_route (Request $request, ObjectManager $manager)
    {
        //Redirection si l'utilisateur est déjà connecté.
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('dashboard',[
                '_username' => $this->getUser()->getNickname(),
                '_uuid' => $this->getUser()->getId()
            ]);
        }

        //L'on affiche le formulaire de connexion si l'utilisateur n'est pas connecté
        return $this->render( 'secur_routes/login.html.twig',[
        ]);
    }

    /**
     * @Route("/admin", name="secur_admin_dashboard")
     *
     */
    public function secur_admin_route (Request $request, ObjectManager $manager, UtilisateurRepository $usersRepository, ChienRepository $ChienRepository)
    {
        //Si l'utilisateur a un role d'admin, l'on lui donne acces à la page
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )
        {
            //L'on recupere tous les utilisateurs et leurs Chien associées pour afficher ensuite
            $allUsers = $usersRepository->findAll();
            $allChien = $ChienRepository->findAll();

            //Affichage contenu pour l'espace admin
            return $this->render ( 'secur_routes/admin_dashboard.html.twig', [
                'Welcome' => $this->getUser()->getNickname(),
                'users' => $allUsers,
                'Chien' => $allChien,
            ]);
        }
        else {
            //Sinon, retour à la page d'accueil
            return $this->redirectToRoute('dashboard');
        }

    }

    /**
     * @Route("/espace_Chien/edition_profile", name="edition_profil_user")
     * @Route("/admin/{uuid_user}", name="edit_info_user_by_admin")
     */
    public function secur_edit_profile (Request $request, ObjectManager $manager, UtilisateurRepository $utilisateurRepository, UserPasswordEncoderInterface $encoder, $uuid_user = null)
    {
        // Si uuid_user existant, alors l'admin est connecté (dans security.yaml, que les admins peuvent acceder à la route avec $uuid comme slug)
        if (!$uuid_user) {
            //Si uuid non existant, alors on recupere les infos utilisateur en cours
            $user = $utilisateurRepository->find($this->getUser()->getId());
        }
        else {
            $user = $utilisateurRepository->find($uuid_user);
        }


        //Creation formulaire, et reactivité aux requetes
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        //Si formulaire est valide on update les donnees utilisateurs
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            //Si utilisateur on le redirige à son espace
            //Si admin, on redirige à l'espace admin
            if (!$uuid_user) {

                //Flash message indication
                $this->addFlash('info', 'Votre profil est à jour !');

                return $this->redirectToRoute('dashboard');
            }
            else {
                //Flash message indication
                $this->addFlash('info', 'Le profil de l\'utilisateur '.$user->getNickname().'('.$user->getEmail().')'.' a été mis à jour.');
                return $this->redirectToRoute('secur_admin_dashboard'); }
        }


        return $this->render('account_routes/edit_user.html.twig', [
            'form_user' => $form->createView(),
            'user' => $user
        ]);
    }



}
