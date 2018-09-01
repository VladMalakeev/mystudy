<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Institute;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use App\Repository\InstituteRepository;
use App\Service\RepositoryManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("management/departments")
 */
class DepartmentController extends Controller
{
    public $manager;

    public function __construct(RepositoryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="department_index")
     */
    public function index()
    {
        $departments = $this->manager->getDepartmentRepository()->findAll();

        if(count($departments)>0){
            return $this->render('department/department_list.html.twig');
        }
        else{
            return $this->redirectToRoute('empty_department');
        }

    }

    /**
     * @Route("/new", name="department_new", methods="GET|POST")
     */
    public function newDepartment(Request $request): Response
    {

        $department = new Department();

        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $institute = $this->manager->getInstituteRepository()->find($request->get('instituteId'));
            $department->setInstitute($institute);
            $em->persist($department);
            $em->flush();
            $this->addFlash('new', 'Кафедра добавлена!');
            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/department_form.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="department_edit", methods="GET|POST")
     */
    public function editDepartment($id, Request $request): Response
    {
        $department = $this->manager->getDepartmentRepository()->find($id);
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('edit', 'Данные отредактированы!');
            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/department_form.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/{id}", name="department_delete", methods="DELETE")
     */
    public function deleteDepartment($id, Request $request): Response
    {
        $department = $this->manager->getDepartmentRepository()->find($id);
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();

            $schedules = $department->getSchedules();
            foreach ($schedules as $schedule){
                $em->remove($schedule);
            }

            $lecturers = $department->getLecturers();
            foreach ($lecturers as $lecturer){
                $em->remove($lecturer);
            }

            $subjects = $department->getSubjects();
            foreach ($subjects as $subject){
                $em->remove($subject);
            }

            $groups = $department->getGroups();
            foreach ($groups as $group){
                $em->remove($group);
            }

            $em->remove($department);
            $em->flush();
        }

        $this->addFlash('delete', 'Кафедра удалена!');
        return $this->redirectToRoute('department_index');
    }

    /**
     * @Route("/empty", name="empty_department")
     */
    public function emptyBlock(){
        return $this->render('department/department_empty.html.twig');
    }
}

