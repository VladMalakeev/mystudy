<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Groups;
use App\Form\GroupsType;
use App\Repository\DepartmentRepository;
use App\Repository\GroupsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("management/structure/department")
 */
class GroupsController extends Controller
{
    /**
     * @Route("/", name="group_index")
     */
    public function index(DepartmentRepository $departmentRepository)
    {
        $departments = $departmentRepository->findAll();

        if(count($departments)>0){
            return $this->redirectToRoute('groups_show', array("short_name" => $departments[0]->getShortName()));
        }
        else{
            return $this->redirectToRoute('empty_department');
        }

    }

    /**
     * @Route("/{short_name}", name="groups_show")
     */
    public function showGroups($short_name): Response
    {
        return $this->render('groups/group_list.html.twig', ['department_name' => $short_name]);
    }

    /**
     * @Route("/{currentDepartment}/{course}/new", name="groups_new")
     */
    public function newGroup($currentDepartment, $course, Request $request): Response
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $currentDepartment));
        $year = date("y")- ($course-1);
        $number=1;
        $groups = $department->getGroups();

        if(count($groups)>0) {
            foreach ($groups as $group) {
                if ($group->getCourse() == $course) {
                    if($group->getNumber()>$number){
                        $number = $group->getNumber();
                    }
                }
            }
        }

        if($request->get('add_group')) {

            $group = new Groups();
            $group->setDepartment($department);
            $group->setYearOfAdmission($year);
            $group->setNumber($request->get('group_number'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            $this->addFlash('new', 'Новая группа добавлена!');
            return $this->redirectToRoute('groups_show', array("short_name" => $currentDepartment));
        }
        return $this->render('groups/group_form.html.twig' , array('department_name' => $currentDepartment, 'year' => $year, 'number' => $number+1));
    }

    /**
     * @Route("/{currentDepartment}/group/{groupId}/edit", name="group_edit")
     */
    public function editGroup(string $currentDepartment, int $groupId, Request $request ): Response
    {
        $group = $this->getDoctrine()->getRepository(Groups::class)->find($groupId);
        $year = $group->getYearOfAdmission();
        $number = $group->getNumber();
        if($request->get('add_group')) {
            $group->setNumber($request->get('group_number'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('edit', 'Данные отредактированы!');
            return $this->redirectToRoute('groups_show', array("short_name" => $currentDepartment));
        }

        return $this->render('groups/group_form.html.twig' , array('department_name' => $currentDepartment, 'year' => $year, 'number' =>$number));
    }

    /**
     * @Route("/{currentDepartment}/group/{groupId}/delete", name="group_delete", methods="DELETE")
     */
    public function deleteGroup(string $currentDepartment, int $groupId, Request $request ): Response
    {
        $group = $this->getDoctrine()->getRepository(Groups::class)->find($groupId);
        if ($this->isCsrfTokenValid('delete'.$groupId, $request->request->get('_token'))) {
            $schedule_array = $group->getSchedules();
            $em = $this->getDoctrine()->getManager();
            foreach ($schedule_array as $schedule){
                $em->remove($schedule);
            }
            $em->remove($group);
            $em->flush();
        }

        $this->addFlash('delete', 'Группа удалена!');
        return $this->redirectToRoute('groups_show',array("short_name" => $currentDepartment));
    }

}
