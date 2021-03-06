<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCarteFormController extends AbstractController
{
    /**
     * @Route("/admin/carte/form", name="admin_carte_form")
     */
    public function index(): Response
    {
        return $this->render('admin/adminCarteForm.html.twig',);
    }
}
