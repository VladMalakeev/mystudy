<?php
namespace App\Service;

use App\Entity\Institute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DBHelper extends AbstractController
{

    public function getInstitute(){
        $id = 1; //толькло 1 институт в бд
        $institute = $this->getDoctrine()->getRepository(Institute::class)->find($id);
        return $institute->getFullName();
    }
}