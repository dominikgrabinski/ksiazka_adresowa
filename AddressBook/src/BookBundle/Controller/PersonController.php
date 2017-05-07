<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use BookBundle\Entity\Person;

class PersonController extends Controller
{
    private function getForm(
            $person,
            $url = false,
            $label = 'Create Person'
            ){
        if($url == false){
            $url = $this->generateUrl('book_person_new');
        }
        $form = $this->createFormBuilder($person)
                ->setAction($url)
                ->setMethod('POST')
                ->add('name')
                ->add('surname')
                ->add('description')
                ->add('photo')
                ->add('save', 'submit', ['label' => $label])
                ->getForm();
        return $form;
                
    }
    
    /**
     * @Route("/new")
     */
    public function newAction(Request $req)
    {
        $newPerson = new Person();
        $form = $this->getForm($newPerson);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($newPerson);
            $em->flush();
            
            return $this->redirect($this->generateUrl('book_person_showall'));
        }
        
        return $this->render('BookBundle:Person:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/modify")
     */
    public function modifyAction(Request $req, $id)
    {
        $repo = $this->getDoctrine()->getRepository('BookBundle:Person');
        $person = $repo->find($id);
        $form = $this->getForm($person, $this->generateUrl('book_person_modify', ['id' => $id]), 'Update Person');
        $form->handleRequest($req);
        
        if($form->isSubmitted()){
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render('BookBundle:Person:modify.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('BookBundle:Person')->find($id);
        $em->remove($person);
        $em->flush();
        return $this->render('BookBundle:Person:delete.html.twig', array(
            
        ));
    }

    /**
     * @Route("/{id}")
     */
    public function idAction($id)
    {
        return $this->render('BookBundle:Person:id.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/")
     */
    public function showAllAction()
    {
        return $this->render('BookBundle:Person:show_all.html.twig', array(
            // ...
        ));
    }

}
