<?php

namespace App\Controller;
use App\Entity\Courses;
use App\Entity\Department;
use App\Entity\Subjects;
use App\Form\SubjectsType;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("management/subjects")
 */
class SubjectsController extends Controller
{
    /**
     * @Route("/", name="subject_index")
     */
    public function index(DepartmentRepository $departmentRepository){
        $departments = $departmentRepository->findAll();

        if(count($departments)>0){
            return $this->redirectToRoute('subject_show', array("currentDepartment" => $departments[0]->getShortName()));
        }
        else{
            return $this->redirectToRoute('empty_department');
        }
    }

    /**
     * @Route("/{currentDepartment}/course/{number}", name="subject_show_course")
     */
    public function showCourse($currentDepartment, $number)
    {
        return $this->render('subjects/subject_list.html.twig', ['department_name' => $currentDepartment, 'current_course' => $number]);
    }

    /**
     * @Route("/{currentDepartment}", name="subject_show")
     */
    public function showSubjects($currentDepartment)
    {
        return $this->render('subjects/subject_list.html.twig', ['department_name' => $currentDepartment, 'current_course' => null]);
    }


    /**
     * @Route("/{currentDepartment}/new", name="new_subjects",  methods="GET|POST")
     */
    public function newSubject(Request $request, $currentDepartment):Response
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $currentDepartment));
        $subject = new Subjects();
        $subject->setDepartment($department);
        $form = $this->createForm(SubjectsType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            $this->addFlash('new', 'Новая дисциплина добавлена!');
            return $this->redirectToRoute('subject_show', ['currentDepartment' => $currentDepartment]);
        }

        return $this->render('subjects/subject_form.html.twig',
            ['form' => $form->createView(),
            'route' => 'subject_show',
            'department_name' => $currentDepartment,
            'current_course' => null]);
    }

    /**
     * @Route("/{currentDepartment}/course/{number}/new", name="new_subjects_course",  methods="GET|POST")
     */
    public function newSubjectCourse( $currentDepartment, $number, Request $request):Response
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $currentDepartment));
        $course = $this->getDoctrine()->getRepository(Courses::class)->findOneBy(array("number" => $number));
        $subject = new Subjects();
        $subject->setDepartment($department);
        $subject->setCourses($course);
        $form = $this->createForm(SubjectsType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            $this->addFlash('new', 'Новая дисциплина добавлена!');
            return $this->redirectToRoute('subject_show_course', ['currentDepartment' => $currentDepartment, 'number' => $number]);
        }

        return $this->render('subjects/subject_form.html.twig',
            ['form' => $form->createView(),
                'route' => 'subject_show_course',
                'department_name' => $currentDepartment,
                'current_course' => $number]);
    }


    /**
     * @Route("/{currentDepartment}/{id}/edit", name="subjects_edit", methods="GET|POST")
     */
    public function editSubject($currentDepartment, $id, Request $request): Response
    {
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->find($id);
        $form = $this->createForm(SubjectsType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('edit', 'Данные отредактированы!');
            return $this->redirectToRoute('subject_show', ['currentDepartment' => $currentDepartment]);
        }

        return $this->render('subjects/subject_form.html.twig',
            ['form' => $form->createView(),
                'route' => 'subject_show',
                'department_name' => $currentDepartment,
                'current_course' => null]);
    }

    /**
     * @Route("/{currentDepartment}/subject/{id}/delete", name="subjects_delete", methods="DELETE")
     */
    public function deleteSubject($currentDepartment, $id, Request $request): Response
    {
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->find($id);
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $schedule_array = $subject->getSchedules();
            foreach ($schedule_array as $schedule){
                $schedule->setSubjectName(null);
            }
            $em->remove($subject);
            $em->flush();
        }

        $this->addFlash('delete', 'Дисциплина удалена!');
        return $this->redirectToRoute('subject_show',array('currentDepartment' => $currentDepartment));
    }
}
