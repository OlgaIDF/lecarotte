<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleFormController extends AbstractController
{
    /**
     * @Route("/admin/article/form", name="admin_article_form")
     */
    public function index(): Response
    {
        return $this->render('admin/adminArticleForm.html.twig',);
    }
}
