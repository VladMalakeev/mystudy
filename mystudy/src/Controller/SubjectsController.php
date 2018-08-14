<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubjectsController extends AbstractController
{
    /**
     * @Route("/subjects", name="subjects")
     */
    public function index()
    {
        return $this->render('subjects/index.html.twig.twig', [
            'controller_name' => 'SubjectsController',
        ]);
    }
}
