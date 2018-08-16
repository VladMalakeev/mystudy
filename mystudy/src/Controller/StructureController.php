<?php

namespace App\Controller;

use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/management/structure")
 */
class StructureController extends Controller
{
    /**
     * @Route("/", name="structure")
     */
    public function index(DepartmentRepository $departmentRepository)
    {
        $departments = $departmentRepository->findAll();

        if(count($departments)>0){
            return $this->redirectToRoute('department_show', array("short_name" => $departments[0]->getShortName()));
        }
        else{
            return $this->redirectToRoute('empty_department');
        }

        /*
        $schedule = "schedule";
        $group = array("name" => "AI162", "schedule" => $schedule);
        $groups = array("AI181", "AI182", "AI182");
        $course_1 = array("number"=> 1, "groups" => $groups);
        $course_2 = array("number"=> 2, "groups" => $groups);
        $course_3 = array("number"=> 3, "groups" => $groups);
        $course_4 = array("number"=> 4, "groups" => $groups);
        $course_5 = array("number"=> 5, "groups" => $groups);
        $courses = array($course_1, $course_2, $course_3, $course_4, $course_5);
        $deppartment_1 = array("name" => "AI", "courses" => $courses);
        $deppartment_2 = array("name" => "AT", "courses" => $courses);
        $departments = array($deppartment_1, $deppartment_2);
        */

       // return $this->render('structure/structure.html.twig.', array("departments" => $departments));
    }

}
