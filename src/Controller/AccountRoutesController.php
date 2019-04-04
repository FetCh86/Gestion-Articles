<?php

namespace App\Controller;

use App\Entity\Chien;
use App\Form\AjoutVideoType;
use App\Repository\ChienRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountRoutesController extends Controller
{
    /**
     * @Route("/espace_Chien", name="dashboard")
     */
    public function index(ChienRepository $ChienRepository)
    {

        //On recupere les Chien associé à l'utilisateur en cours
        $Chien = $ChienRepository->findBy([
            'iduser' => $this->getUser()->getId(),
        ]);

        //Affichage du contenu de l'espace utilisateur avec ses Chien
        return $this->render('account_routes/index.html.twig', [
            'username' => $this->getUser()->getNickname(),
            'email' => $this->getUser()->getEmail(),
            'Chien_user' => $Chien,
        ]);
    }

    /**
     * @Route("/espace_Chien/ajout_video", name="add_video_user")
     */
    public function add_video (Request $request, ObjectManager $manager)
    {
        //Creation nouvel objet video, le bindant au formulaire de video
        $video = new Chien();

        $form = $this->createForm(AjoutVideoType::class, $video);

        $form->handleRequest($request);

        //Si formulaire valide, on update
        if ($form->isSubmitted() && $form->isValid()) {

            $video->setIdUser($this->getUser());
            //$video->setByUser($this->getUser()->getNickname());
            //$video->setCreatedAt( new \DateTime() );
            $manager->persist($video);
            $manager->flush();

            //Flash message indication
            $this->addFlash('info', 'La video "'.$video->getTitle().'" a bien été crée.');

            //Retour à l'espace utilisateur
            return $this->redirectToRoute('dashboard');
        }

        //Affichage du formulaire pour ajouter une video
        return $this->render( 'account_routes/add_video.html.twig', [
            'username' => $this->getUser()->getNickname(),
            'form_video' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace_Chien/edition_video", name="edit_video_user")
     * @Route("/admin/edition_video/{uuid_user}/{uuid_video}", name="edit_video_user_by_admin")
     */
    public function edit_video (Request $request, ObjectManager $manager, ChienRepository $ChienRepository, $uuid_user = null, $uuid_video = null)
    {
        //Definition à vide de la variable $id_video, pour déterminer is oui ou non, video trouvée
        $id_video = "";

        //Si le parametre GET "v_id" est existant, alors on le recupere
        if ($request->get("v_id")) {
            $id_video = $request->get("v_id");
        }
        else {
            //Sinon, on recupere le $uuid_video qui est passé en tant que slug
            $id_video = $uuid_video;
        }

        //Requete recherche video dans la base de données
        $video = $ChienRepository->find($id_video);

        //Si video non existante ou non appartenante à un utilisateur, à part si detection de l'utilisation route admin
        if (!$video || $video->getIdUser()->getId() != $this->getUser()->getId() && !$uuid_video)
        {
            //Alors redirection vers le dashboard, menu principal
            return $this->redirectToRoute('dashboard');
        }

        //Creation formulaire et réactivité aux requetes
        $form = $this->createForm(AjoutVideoType::class, $video);
        $form->handleRequest($request);

        //Si formulaire soumit et valide, alors on enregistre les modifications
        if ($form->isSubmitted() && $form->isValid())
        {
            //Si l'utilisateur possède bien la video, on modifie ses infos
            //Si l' $uuid_user est present, alors on détermine que c'est un admin, on autorise donc la modification
            if ($video->getIdUser()->getId() === $this->getUser()->getId() || $uuid_user) {

                $manager->persist($video);
                $manager->flush();

                //Flash message indication
                $this->addFlash('info', 'Les infos de la video "'.$video->getTitle().'" ont été changés.');
            }

            //Si $uuid_video existant, alors on determine que l'utilisateur est admin
            //Auquel cas, on redirige à l'espace admin pour les admins, et l'espace user pour les users
            if (!$uuid_video)
                return $this->redirectToRoute('dashboard');
            else
                return $this->redirectToRoute('secur_admin_dashboard');
        }

        //Affichage du formulaire pour editer la video
        return $this->render ( 'account_routes/edit_video.html.twig', [
            'form_video' => $form->createView(),
            'video_to_edit' => $video
        ]);
    }

    /**
     * @Route("/espace_Chien/suppression_video", name="delete_video_user")
     * @Route("/admin/suppression_video/{uuid_user}/{uuid_video}", name="delete_video_user_by_admin")
     */
    public function delete_video (Request $request, ObjectManager $manager, ChienRepository $ChienRepository, $uuid_user = null, $uuid_video = null)
    {
        //Si le parametre GET "v_id" est existant, alors on le recupere
        if ($request->get("v_id")) {
            $id_video = $request->get("v_id");
        }
        else {
            //Sinon, on recupere le $uuid_video qui est passé en tant que slug
            $id_video = $uuid_video;
        }

        //Requete recherche video dans la base de données
        $video = $ChienRepository->find($id_video);

        //Si video non existante ou non appartenante à un utilisateur, à part si detection de l'utilisation route admin
        if (!$video || $video->getIdUser()->getId() != $this->getUser()->getId() && !$uuid_video)
        {
            //Alors redirection vers le dashboard, menu principal
            return $this->redirectToRoute('dashboard');
        }

        //Si l'utilisateur possède bien la video, on la supprime
        //Si l' $uuid_user est present, alors on détermine que c'est un admin, on autorise donc la suppression
        if ($video->getIdUser()->getId() === $this->getUser()->getId() || $uuid_user) {

            $manager->remove($video);
            $manager->flush();

            //Flash message indication
            $this->addFlash('info', 'La vidéo "'.$video->getTitle().'" a bien été supprimée.');
        }

        //Si $uuid_video existant, alors on determine que l'utilisateur est admin
        //Auquel cas, on redirige à l'espace admin pour les admins, et l'espace user pour les users
        if (!$uuid_video)
            return $this->redirectToRoute('dashboard');
        else
            return $this->redirectToRoute('secur_admin_dashboard');
    }

}
