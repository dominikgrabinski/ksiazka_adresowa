<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use BookBundle\Entity\Person;
use BookBundle\Entity\Address;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
        if($person == NULL) {
            $resp = new Response ('Brak id: ' .$id. ' w bazie');
        }else{
            $em->remove($person);
        
        $em->flush();
        
        $resp = new Response ('Usunięto wpis o id: '.$id);
        }
      return $resp;
//        $this->render('BookBundle:Person:delete.html.twig', array(
//            'person' => $person
//        ));
    }



    /**
     * @Route("/showAll")
     */
    public function showAllAction()
    {
        $repo2 = $this->getDoctrine()->getRepository('BookBundle:Person')->findAll();
        //$count = count($repo2);
        return $this->render('BookBundle:Person:show_all.html.twig', array(
            'all' => $repo2 //, 'number' => $count
        ));
    }

    /**
     * @Route("/show/{id}")
     */
    public function idAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('BookBundle:Person')->find($id);
//      //  $show = __toString($repo);
//        $resp = new Response ('name: '.$repo->getName().'</br> surname: '.$repo->getSurname().'</br> description: '.$repo->getDescription().'</br> photo: '.$repo->getPhoto() );
//        return $resp;
        
        
        return $this->render('BookBundle:Person:id.html.twig', array(
            'person' => $repo //, 'number' => $count
        ));
    }

    private function getAddressForm(
            $address,
            $url = false,
            $label = 'Create Address'
            ){
        if($url == false){
            $url = $this->generateUrl('book_person_newaddress');
        }
        $form = $this->createFormBuilder($address)
                ->setAction($url)
                ->setMethod('POST')
                ->add('city')
                ->add('street')
                ->add('home_number')
                ->add('flat_number')
                ->add('person', EntityType::class,[
                    'class' => 'BookBundle\Entity\Person',
                    'choice_label' => 'name'])
                ->add('save', 'submit', ['label' => $label])
                ->getForm();
        return $form;
                
    }
    
    /**
     * @Route("/newAddress")
     */
    public function newAddressAction(Request $req)
    {
        $newAddress = new Address();
        $form = $this->getAddressForm($newAddress);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($newAddress);
            $em->flush();
            
            return $this->redirect($this->generateUrl('book_person_showalladdressess'));
        }
        
        return $this->render('BookBundle:Person:newAddress.html.twig', array(
            'form' => $form->createView()
        ));
    }    
    
    /**
     * @Route("/showAllAddressess")
     */
    public function showAllAddressessAction()
    {
        $repo3 = $this->getDoctrine()->getRepository('BookBundle:Address')->findAll();
        //$count = count($repo2);
        return $this->render('BookBundle:Person:show_all_addressess.html.twig', array(
            'all' => $repo3 //, 'number' => $count
        ));
    }    
    
}
