<?php

namespace App\Controller;

use App\Repository\CarteRepository;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CarteRepository $carteRepository, ArticlesRepository $articlesRepository)
    {
       
       $carte = $carteRepository->findAll();
       $articles = $articlesRepository->findAll();
      

        return $this->render('home/index.html.twig', [
            'carte' => $carte,
            'articles' => $articles,
            
        ]);
    }





}
