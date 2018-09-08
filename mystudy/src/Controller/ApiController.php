<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Service\RepositoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{

    public $manager;

    public function __construct(RepositoryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/institutes", name="api_institutes")
     */
    public function getInstitutes()
    {

        $institutes = $this->manager->getInstituteRepository()->findAll();

        foreach ($institutes as $institute){
            $inctitutes[] = array(
                                       'instituteName' => $institute->getFullName(),
                                       'shortName' => $institute->getShortName(),
                                       'description' => $institute->getDescription());
        }

        return new Response(json_encode($inctitutes, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @Route("/departments/{instituteName}", name="api_departments")
     */
    public function getDepartmens($instituteName){
        $institute = $this->manager->getInstituteRepository()->findOneBy(array('short_name' => $instituteName));
        $departmentData = $institute->getDepartments();
        foreach ($departmentData as $department){
            $departments[] = array(
                'fullName' => $department->getFullName(),
                'shortName' => $department->getShortName(),
                'description' => $department->getDescription());
        }
        return new Response(json_encode($departments, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @Route("/groups/{departmentName}", name="api_all_groups")
     */
    public function getAllGroups($departmentName){
        if($department = $this->manager->getDepartmentRepository()->findOneBy(array('short_name' => $departmentName))) {

            foreach ($department->getGroups() as $group) {
                $groupData[] = array('id' => $group->getId(),
                                     'name' => $group->getFullName(),
                                     'course' => $group->getCourse());
            }
            return new Response(json_encode($groupData, JSON_UNESCAPED_UNICODE));
        }
        return new Response("Кафедра $departmentName не существует");
    }

    /**
     * @Route("/groups/{departmentName}/{course}", name="api_group")
     */
    public function getGroup($departmentName, $course){
        if($department = $this->manager->getDepartmentRepository()->findOneBy(array('short_name' => $departmentName)))
        {
            if($this->manager->getCoursesRepository()->findOneBy(array('number' => $course))){
                $groupArray = array();
                foreach ($department->getGroups() as $group){
                    if($group->getCourse() == $course){
                        $groupArray[] = array( 'id' => $group->getId(),
                                               'name' => $group->getFullName(),
                                                'course' => $group->getCourse());
                    }
                }
                return new Response(json_encode($groupArray, JSON_UNESCAPED_UNICODE));
            }
            return new Response("Курс $course не существует");
        }
        return new Response("Кафедра $departmentName не существует");
    }

    /**
     * @Route("/subjects/{departmentName}", name="api_all_subjects")
     */
    public function getAllSubjects($departmentName){
        if($department = $this->manager->getDepartmentRepository()->findOneBy(array('short_name' => $departmentName))) {
            $subjects = array();
            foreach ($department->getSubjects() as $subject){
                $subjects[] = array(
                                        'name' => $subject->getName(),
                                        'description' => $subject->getDescription());
            }
            return new Response(json_encode($subjects, JSON_UNESCAPED_UNICODE));
        }
        return new Response("Кафедра $departmentName не существует");
    }

    /**
     * @Route("/subjects/{departmentName}/{courseNumber}", name="api_subjects")
     */
    public function getSubject($departmentName, $courseNumber){
        if($department = $this->manager->getDepartmentRepository()->findOneBy(array('short_name' => $departmentName)))
        {
            if($this->getDoctrine()->getRepository(Courses::class)->findOneBy(array('number' => $courseNumber))){
                $subjects = array();
                foreach ($department->getSubjects() as $subject){
                    foreach ($subject->getCourses() as $course){
                        if($course->getNumber() == $courseNumber){
                            $subjects[] = array(
                                                 'name' => $subject->getName(),
                                                 'description' => $subject->getDescription());

                        }
                    }
                }
                return new Response(json_encode($subjects, JSON_UNESCAPED_UNICODE));
            }
            return new Response("Курс $courseNumber не существует");
        }
        return new Response("Кафедра $departmentName не существует");
    }

    /**
     * @Route("/lecturers/{departmentName}", name="api_lecturers")
     */
    public function getLecturers($departmentName, Request $request){
        if($department = $this->manager->getDepartmentRepository()->findOneBy(array('short_name' => $departmentName)))
        {

            foreach ($department->getLecturers() as $lecturer){
                $lecturers[] = array(
                                    'firstName' => $lecturer->getFirstName(),
                                    'lastName' => $lecturer->getLastName(),
                                    'patronymic' => $lecturer->getPatronymic(),
                                    'description' => $lecturer->getDescription(),
                                    'photo' => $request->getHttpHost().'/uploads/photos/'.$lecturer->getPhoto());
            }
            return new Response(json_encode($lecturers, JSON_UNESCAPED_UNICODE));
        }
        return new Response("Кафедра $departmentName не существует");
    }

    /**
     * @Route("/schedule/{id}/", name="api_schedule")
     */
    public function getSchedule($id){
        if($group = $this->manager->getGroupsRepository()->find($id))
        {
            foreach ($group->getSchedules() as $schedule ){
                 if($schedule->getSubjectName() != null)
                      $subject = $schedule->getSubjectName()->getName();
                 else $subject = null;

                 if($schedule->getLecturer() != null)
                    $lecturer = $schedule->getLecturer()->getNameInfo();
                 else $lecturer = null;

                $scheduleArray[]  = array(  'week' => $schedule->getWeek(),
                                            'day_of_week' => $schedule->getDayOfWeek(),
                                            'lesson_number' => $schedule->getLessonNumber(),
                                            'subject_name' => $subject,
                                            'lecturer' => $lecturer,
                                            'classroom' => $schedule->getClassroom(),
                                            );
                                           // 'department' => $schedule->getDepartment(),
                                           // 'group_id' => $schedule->getGroups()->getId());
            }

            return new Response(json_encode($scheduleArray, JSON_UNESCAPED_UNICODE));
        }
        return new Response("Группа с id - $id  не существует");
    }

    /**
     * @Route("/news/{instituteName}", name="api_news")
     */
    public function getNews($instituteName, Request $request){
        $institute = $this->manager->getInstituteRepository()->findOneBy(array('short_name' => $instituteName));
        $newsData = $institute->getNews();
        foreach ($newsData as $news){
            $newsArray[] = array(
                'header' => $news->getHeader(),
                'description' => $news->getDescription(),
                'urlImg' => $request->getHttpHost().'/uploads/images/'.$news->getImage());
        }
        return new Response(json_encode($newsArray, JSON_UNESCAPED_UNICODE));
    }
}


