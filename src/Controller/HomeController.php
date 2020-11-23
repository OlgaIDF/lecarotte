<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\CarteRepository;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CarteRepository $carteRepository, ArticlesRepository $articlesRepository,\Swift_Mailer $mailer, Request $request )
    {
        $formulaireContact = $this->createForm(ContactType::class);
        $formulaireContact->handleRequest($request);

        if($formulaireContact->isSubmitted() && $formulaireContact->isValid()){
            $infos = $formulaireContact->getData();
            // On crée le message
            $mail = (new \Swift_Message('Nouveau contact'))
                ->setFrom($infos['email'])
                ->setTo('restaurant.lacarotte@gmail.com')
                ->setBody(
                    $this->renderView(
                        'contact/email.html.twig', [
                            'nom' => $infos['nom'],
                            'prenom' => $infos['prenom'],
                             'email' => $infos['email'],
                            'message' => $infos['message']
                        ],
                        'text/html'
                    )
                );
            $mailer->send($mail);
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé'
            );
            
        }


       
    
       $carte = $carteRepository->findAll();
       $articles = $articlesRepository->findAll();
      

        return $this->render('home/index.html.twig', [
            'carte' => $carte,
            'articles' => $articles,
            'formulaireDeContact' => $formulaireContact->createView()
            
        ]);
    }





}
