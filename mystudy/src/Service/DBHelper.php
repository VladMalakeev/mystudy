<?php
namespace App\Service;

use App\Entity\Department;
use App\Entity\Institute;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DBHelper extends AbstractController
{

    public function getInstitute(){
        $id = 1; //толькло 1 институт в бд
        $institute = $this->getDoctrine()->getRepository(Institute::class)->find($id);
        return $institute->getFullName();
    }

    public  function getDepartments(){
        return  $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();
    }

    public function getGroups(string $short_name){
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $short_name));
        $groups = $department->getGroups();

        $year = date("y");

        $course_1 = array();
        $course_2 = array();
        $course_3 = array();
        $course_4 = array();
        $course_5 = array();

        foreach($groups as $group){
            switch($year-($group->getYearOfAdmission()-1)){
                case 1: $course_1[] = $group; break;
                case 2: $course_2[] = $group; break;
                case 3: $course_3[] = $group; break;
                case 4: $course_4[] = $group; break;
                case 5: $course_5[] = $group; break;
                default: break;
            }
        }

        $courses = array("1" => $course_1,
            "2" => $course_2,
            "3" => $course_3,
            "4" => $course_4,
            "5" => $course_5);
        return $courses;
    }

    public function getSubjects(string $short_name){
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $short_name));
        return $department->getSubjects();
    }

    public function getRecturers( string $short_name){
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $short_name));
        return $department->getRecturers();
    }
}