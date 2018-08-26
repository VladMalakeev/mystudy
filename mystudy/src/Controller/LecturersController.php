<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Lecturers;
use App\Form\LecturersType;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

/**
 * @Route("/management/lecturers")
 */
class LecturersController extends Controller
{
    /**
     * @Route("/", name="lecturers_index")
     */
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        if(count($departments)>0){
            return $this->redirectToRoute('lecturers_show', array("currentDepartment" => $departments[0]->getShortName()));
        }
        else{
            return $this->redirectToRoute('empty_department');
        }
    }

    /**
     * @Route("/{currentDepartment}/new", name="lecturers_new", methods="GET|POST")
     */
    public function new($currentDepartment, Request $request, FileUploader $fileUploader): Response
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $currentDepartment));
        $lecturer = new Lecturers();
        $lecturer->setDepartment($department);
        $form = $this->createForm(LecturersType::class, $lecturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();
            $fileName = $fileUploader->upload($file);

            $lecturer->setPhoto($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($lecturer);
            $em->flush();

            $this->addFlash('notice', 'Преподаватель добавлен!');
            return $this->redirectToRoute('lecturers_show', array('currentDepartment' => $currentDepartment));
        }

        return $this->render('lecturers/form.html.twig', [
            'lecturer' => $lecturer,
            'photo' => null,
            'form' => $form->createView(),
            'department_name' => $currentDepartment
        ]);
    }

    /**
     * @Route("/{currentDepartment}", name="lecturers_show", methods="GET")
     */
    public function show($currentDepartment): Response
    {
        return $this->render('lecturers/lecturers.html.twig', ['department_name' => $currentDepartment]);
    }

    /**
     * @Route("/{currentDepartment}/{id}/edit", name="lecturers_edit", methods="GET|POST")
     */
    public function edit($currentDepartment, $id, Request $request, FileUploader $fileUploader): Response
    {
        $lecturer = $this->getDoctrine()->getRepository(Lecturers::class)->find($id);
        $photo = $lecturer->getPhoto();
        $lecturer->setPhoto(null);
        $form = $this->createForm(LecturersType::class, $lecturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['photo']->getData();
            $fileName = $fileUploader->upload($file);
            $lecturer->setPhoto($fileName);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Данные отредактированы!');
            return $this->redirectToRoute('lecturers_show', ['currentDepartment' => $currentDepartment]);
        }

        return $this->render('lecturers/form.html.twig', [
            'department_name' => $currentDepartment,
            'photo' => $photo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{currentDepartment}/lecturer/{id}/delete", name="lecturers_delete", methods="DELETE")
     */
    public function delete($currentDepartment, $id, Request $request, Lecturers $lecturer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lecturer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lecturer);
            $em->flush();
        }

        return $this->redirectToRoute('lecturers_show', array('currentDepartment' => $currentDepartment));
    }
}
