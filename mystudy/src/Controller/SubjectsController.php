<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("management/subjects", name="subjects")
 */
class SubjectsController extends AbstractController
{
    /**
     * @Route("/", name="subjects")
     */
    public function index()
    {
        return $this->render('subjects/subjects.html.twig');
    }
}
