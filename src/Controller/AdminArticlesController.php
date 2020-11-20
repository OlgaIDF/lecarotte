<?php

namespace App\Controller;

use App\Form\CarteType;
use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function index(ArticlesRepository $articlesRepository)
    {
        $articles = $articlesRepository->findAll();

        return $this->render('admin/adminArticles.html.twig', [
            'articles' => $articles,
        ]);
    }
    /**
     * @Route("/admin/articles/create", name="article_create")
     */
    public function createArticle(Request $request)
    {
        $article = new Articles();
        $article->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $imgArticle = $form['img']->getData();

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $nomImgArticle = md5(uniqid()); // nom unique
                $extensionImgArticle = $imgArticle->guessExtension(); // récupérer l'extension du picto
                $newNomImgArticle = $nomImgArticle . '.' . $extensionImgArticle; // recomposer un nom du picto

                try { // on tente d'importer l'image


                    $imgArticle->move(
                        $this->getParameter('dossier_photos_articles'),
                        $newNomImgArticle
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        'Une erreur est survenue lors de l\'importation d\'image'
                    );
                }

                $article->setImg($newNomImgArticle); // nom pour la base de données

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($article);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Le article a bien été modifiée'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'Une erreur est survenue'
                );
            }
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/adminArticleForm.html.twig', [
            'formulaireArticle' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/update-{id}", name="article_update")
     */
    public function updateArticle(ArticlesRepository $articlesRepository, $id, Request $request)
    {
        $article = $articlesRepository->find($id);

        $oldNomImgArticle = $article->getImg();
        $oldCheminImgArticle = $this->getParameter('dossier_photos_articles') . '/' . $oldNomImgArticle;

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $imgArticle = $form['img']->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($oldNomImgArticle != null) {
                unlink($oldCheminImgArticle);
            }


            $nomImgArticle = md5(uniqid()); // nom unique
            $extensionImgArticle = $imgArticle->guessExtension(); // récupérer l'extension du picto
            $newNomImgArticle = $nomImgArticle . '.' . $extensionImgArticle; // recomposer un nom du picto

            try { // on tente d'importer le picto                                      
                $imgArticle->move(
                    $this->getParameter('dossier_photos_articles'),
                    $newNomImgArticle
                );
            } catch (FileException $e) {
                $this->addFlash(
                    'danger',
                    'Une erreur est survenue lors de l\'importation d\'image'
                );
            }

            $article->setImg($newNomImgArticle); // nom pour la base de données

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article);
            $manager->flush();
            $this->addFlash(
                'success',
                'Le article a bien été modifiée'
            );

            return $this->redirectToRoute('admin_articles');
        }
        return $this->render('admin/adminArticleForm.html.twig', [
            'formulaireArticle' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/delete-{id}", name="article_delete")
     */
    public function deleteArticle(ArticlesRepository $articlesRepository, $id)
    {
        $article = $articlesRepository->find($id);

        // récupérer le nom et le chemin de l'image à supprimer
        $nomImgArticle = $article->getImg();
        $cheminImgArticle = $this->getParameter('dossier_photos_articles') . '/' . $nomImgArticle;

        // supprimer img1
        if ($nomImgArticle != null) {
            unlink($cheminImgArticle);
        }



        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le article a bien été supprimée'
        );



        return $this->redirectToRoute('admin_articles');
    }
}
