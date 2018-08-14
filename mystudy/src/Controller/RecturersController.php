<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecturersController extends AbstractController
{
    /**
     * @Route("/recturers", name="recturers")
     */
    public function index()
    {
        return $this->render('recturers/index.html.twig.twig', [
            'controller_name' => 'RecturersController',
        ]);
    }
}
