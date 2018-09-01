<?php

namespace App\Controller;

use App\Entity\Institute;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * @Route("/", name="news_index", methods="GET")
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/news_list.html.twig');
    }

    /**
     * @Route("/new", name="news_new", methods="GET|POST")
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $form['image']->getData();
            if($file != '') {
                $fileName = $fileUploader->uploadImages($file);
                $news->setImage($fileName);
            }

            $institute = $this->getDoctrine()->getRepository(Institute::class)->find($request->get('institute'));
            $news->setInstitute($institute);
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/news_form.html.twig', [
            'form' => $form->createView(), 'news' => $news, 'image' =>null
        ]);
    }

    /**
     * @Route("/{id}/edit", name="news_edit", methods="GET|POST")
     */
    public function edit(int $id, Request $request,FileUploader $fileUploader): Response
    {
        $news = $this->getDoctrine()->getRepository(News::class)->find($id);
        $image = $news->getImage();
        $news->setImage(null);
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['image']->getData();
            if($file != '') {
                $fileName = $fileUploader->uploadImages($file);
                $news->setImage($fileName);
            }
            else  $news->setImage($image);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/news_form.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
            'image' => $image
        ]);
    }

    /**
     * @Route("/{id}", name="news_delete", methods="DELETE")
     */
    public function delete(int $id, Request $request): Response
    {
        $news = $this->getDoctrine()->getRepository(News::class)->find($id);
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($news);
            $em->flush();
        }

        return $this->redirectToRoute('news_index');
    }
}
