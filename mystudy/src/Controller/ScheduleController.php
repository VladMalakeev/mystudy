<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Groups;
use App\Entity\Lecturers;
use App\Entity\Schedule;
use App\Entity\Subjects;
use App\Form\ScheduleType;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("management/structure/department")
 */
class ScheduleController extends Controller
{

    /**
     * @Route("/{currentDepartment}/group/{groupId}/schedule/new", name="schedule_new", methods="GET|POST")
     */
    public function newSchedule(string $currentDepartment, int $groupId, Request $request): Response
    {
        $group = $this->getDoctrine()->getRepository(Groups::class)->find($groupId);

        if ($request->get('submit_schedule')) {
            $form = $request->get('schedule');
            $em = $this->getDoctrine()->getManager();

                foreach ($form as $key_week => $week) {
                    foreach ($week as $key_day => $day) {
                        foreach ($day as $key_lesson => $lesson) {

                            $schedule = new Schedule();
                            $schedule->setDepartment($group->getDepartment());
                            $schedule->setGroup($group);

                            $schedule->setWeek($key_week);
                            $schedule->setDayOfWeek($key_day);
                            $schedule->setLessonNumber($key_lesson);

                            $current_subject = array_shift($lesson);
                            if (!$current_subject == null) {
                                $subject = $this->getDoctrine()->getRepository(Subjects::class)->find($current_subject);
                                $schedule->setSubjectName($subject);
                            }


                            $current_lecturer = array_shift($lesson);
                            if (!$current_lecturer == null) {
                                $lecturer = $this->getDoctrine()->getRepository(Lecturers::class)->find($current_lecturer);
                                $schedule->setLecturer($lecturer);
                            }

                            $current_classroom = (array_shift($lesson));
                            if ($current_classroom == '') {
                                $schedule->setClassroom(null);
                            } else $schedule->setClassroom($current_classroom);

                                $em->persist($schedule);
                        }
                    }
                }

            $em->flush();
            $this->addFlash('new', 'Новое расписание созданно!');
            return $this->redirectToRoute('groups_show', ['short_name' => $currentDepartment]);
        }
        return $this->render('schedule/schedule_form.html.twig', ['schedule_array' => array(), 'group' => $group, 'department_name' => $currentDepartment]);
    }

    /**
     * @Route("/{currentDepartment}/group/{groupId}/schedule/edit", name="schedule_edit", methods="GET|POST")
     */
    public function editSchedule(string $currentDepartment, int $groupId, Request $request): Response
    {
        $group = $this->getDoctrine()->getRepository(Groups::class)->find($groupId);
        $schedule_array = $group->getSchedules();
        if ($request->get('submit_schedule')) {
            $form = $request->get('schedule');

            foreach ($form as $key_week => $week) {
                foreach ($week as $key_day => $day) {
                    foreach ($day as $key_lesson => $lesson) {
                        foreach ( $schedule_array as $schedule){
                            if($schedule->getWeek() == $key_week &&
                                $schedule->getDayOfWeek() == $key_day &&
                                $schedule->getLessonNumber() == $key_lesson){

                                $current_subject = array_shift($lesson);
                                if (!$current_subject == null) {
                                    $subject = $this->getDoctrine()->getRepository(Subjects::class)->find($current_subject);
                                    $schedule->setSubjectName($subject);
                                }

                                $current_lecturer = array_shift($lesson);
                                if (!$current_lecturer == null) {
                                    $lecturer = $this->getDoctrine()->getRepository(Lecturers::class)->find($current_lecturer);
                                    $schedule->setLecturer($lecturer);
                                }

                                $current_classroom = (array_shift($lesson));
                                if ($current_classroom == '') {
                                    $schedule->setClassroom(null);
                                } else $schedule->setClassroom($current_classroom);

                                $this->getDoctrine()->getManager()->flush();
                            }
                        }
                    }
                }
            }
            $this->addFlash('edit', 'Данные отредактированы!');
            return $this->redirectToRoute('groups_show', ['short_name' => $currentDepartment]);
        }

        return $this->render('schedule/schedule_form.html.twig', ['schedule_array' => $schedule_array, 'group' => $group, 'department_name' => $currentDepartment]);
    }

}
