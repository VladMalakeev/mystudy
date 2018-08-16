<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Recturers;
use App\Form\RecturersType;
use App\Repository\DepartmentRepository;
use App\Repository\RecturersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/management/recturers")
 */
class RecturersController extends Controller
{
    /**
     * @Route("/", name="recturers_index")
     */
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        if(count($departments)>0){
            return $this->redirectToRoute('recturers_show', array("currentDepartment" => $departments[0]->getShortName()));
        }
        else{
            return $this->redirectToRoute('empty_department');
        }
    }

    /**
     * @Route("/{currentDepartment}/new", name="recturers_new", methods="GET|POST")
     */
    public function new($currentDepartment, Request $request): Response
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(array("short_name" => $currentDepartment));
        $recturer = new Recturers();
        $recturer->setDepartment($department);
        $form = $this->createForm(RecturersType::class, $recturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recturer);
            $em->flush();

            return $this->redirectToRoute('recturers_show', array('currentDepartment' => $currentDepartment));
        }

        return $this->render('recturers/new.html.twig', [
            'recturer' => $recturer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{currentDepartment}", name="recturers_show", methods="GET")
     */
    public function show($currentDepartment): Response
    {
        return $this->render('recturers/recturers.html.twig', ['department_name' => $currentDepartment]);
    }

    /**
     * @Route("/{id}/edit", name="recturers_edit", methods="GET|POST")
     */
    public function edit(Request $request, Recturers $recturer): Response
    {
        $form = $this->createForm(RecturersType::class, $recturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recturers_edit', ['id' => $recturer->getId()]);
        }

        return $this->render('recturers/edit.html.twig', [
            'recturer' => $recturer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{currentDepartment}/recturer/{id}/delete", name="recturers_delete", methods="DELETE")
     */
    public function delete($currentDepartment, $id,Request $request, Recturers $recturer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recturer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recturer);
            $em->flush();
        }

        return $this->redirectToRoute('recturers_show', array('currentDepartment' => $currentDepartment));
    }
}
