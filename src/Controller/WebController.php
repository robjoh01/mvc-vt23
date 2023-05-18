<?php

namespace App\Controller;

use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WebController extends AbstractController
{
    #[Route("/metrics", name: "metrics")]
    public function cardHome(): Response
    {
        return $this->render('metrics.html.twig');
    }
}
