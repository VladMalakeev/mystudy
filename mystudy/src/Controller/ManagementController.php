<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ManagementController extends AbstractController
{
    /**
     * @Route("/management", name="management")
     */
    public function index()
    {
        return $this->redirectToRoute('group_index');
    }

    /**
     * @Route("/management/structure", name="structure")
     */
    public function structure()
    {
        return $this->redirectToRoute('group_index');
    }
}
