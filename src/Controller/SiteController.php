<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('site/index.html.twig');
    }
}
