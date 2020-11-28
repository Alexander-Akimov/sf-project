<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);

        //dump($request); die;

        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();
            //dump($data);
            $em->persist($note);
            $em->flush();

           return $this->redirectToRoute("index");
        }

        $notes = $em->getRepository(Notes::class)->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView(),
            'notes' => $notes
        ]);
    }
    /**
     * @Route("/remove/{note}", name="remove_note")
     */
    public function removeNote(Notes $note, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($note);
        $em->flush();

        return $this->redirectToRoute("index");
    }
}
