<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BookController extends Controller
{
    /**
     * @Route("/first")
     */
    public function firstAction()
    {
        return $this->render('BookBundle:Book:first.html.twig', array(
            // ...
        ));
    }

}
