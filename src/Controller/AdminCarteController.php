<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Form\CarteType;
use App\Repository\CarteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminCarteController extends AbstractController
{
    /**
     * @Route("/admin/carte", name="admin_carte")
     */
    public function index(CarteRepository $carteRepository)
    {
        $carte = $carteRepository->findAll();

        return $this->render('admin/adminCarte.html.twig', [
            'carte' => $carte,
        ]);
    }
    /**
     * @Route("/admin/carte/create", name="carte_create")
     */
    public function createCarte(Request $request)
    {
        $menu = new Carte();
        $form = $this->createForm(CarteType::class, $menu);
        $form->handleRequest($request);

        $imgMenu = $form['img']->getData();

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $nomImgMenu = md5(uniqid()); // nom unique
                $extensionImgMenu = $imgMenu->guessExtension(); // récupérer l'extension du picto
                $newNomImgMenu = $nomImgMenu . '.' . $extensionImgMenu; // recomposer un nom du picto

                try { // on tente d'importer l'image


                    $imgMenu->move(
                        $this->getParameter('dossier_photos_carte'),
                        $newNomImgMenu
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        'Une erreur est survenue lors de l\'importation d\'image'
                    );
                }

                $menu->setImg($newNomImgMenu); // nom pour la base de données

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($menu);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Le menu a bien été ajouté'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'Une erreur est survenue'
                );
            }
            return $this->redirectToRoute('admin_carte');
        }

        return $this->render('admin/adminCarteForm.html.twig', [
            'formulaireMenu' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/carte/update-{id}", name="carte_update")
     */
    public function updateMenu(CarteRepository $carteRepository, $id, Request $request)
    {
        $menu = $carteRepository->find($id);

        $oldNomImgMenu = $menu->getImg();
        $oldCheminImgMenu = $this->getParameter('dossier_photos_carte') . '/' . $oldNomImgMenu;

        $form = $this->createForm(CarteType::class, $menu);
        $form->handleRequest($request);

        $imgMenu = $form['img']->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($oldNomImgMenu != null) {
                unlink($oldCheminImgMenu);
            }


            $nomImgMenu = md5(uniqid()); // nom unique
            $extensionImgMenu = $imgMenu->guessExtension(); // récupérer l'extension du picto
            $newNomImgMenu = $nomImgMenu . '.' . $extensionImgMenu; // recomposer un nom du picto

            try { // on tente d'importer le picto                                      
                $imgMenu->move(
                    $this->getParameter('dossier_photos_carte'),
                    $newNomImgMenu
                );
            } catch (FileException $e) {
                $this->addFlash(
                    'danger',
                    'Une erreur est survenue lors de l\'importation d\'image'
                );
            }

            $menu->setImg($newNomImgMenu); // nom pour la base de données

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($menu);
            $manager->flush();
            $this->addFlash(
                'success',
                'Le menu a bien été modifié'
            );

            return $this->redirectToRoute('admin_carte');
        }
        return $this->render('admin/adminCarteForm.html.twig', [
            'formulaireMenu' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/carte/delete-{id}", name="carte_delete")
     */
    public function deleteMenu(CarteRepository $carteRepository, $id)
    {
        $menu = $carteRepository->find($id);

        // récupérer le nom et le chemin de l'image à supprimer
        $nomImgMenu = $menu->getImg();
        $cheminImgMenu = $this->getParameter('dossier_photos_carte') . '/' . $nomImgMenu;

        // supprimer img1
        if ($nomImgMenu != null) {
            unlink($cheminImgMenu);
        }

        

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($menu);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le menu a bien été supprimé'
        );



        return $this->redirectToRoute('admin_carte');
    }
}
