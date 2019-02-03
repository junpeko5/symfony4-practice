<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     * @param string $msg
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($msg = 'Hello!')
    {
        return $this->render('hello/index.html.twig', [
            'controller' => 'HelloController',
            'action' => 'index',
            'prev_action' => '(none)',
            'message' => $msg,
        ]);
    }

    /**
     * @Route("/other/{action}/{msg}", name="other")
     * @param $action
     * @param $msg
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function other($action, $msg)
    {
        return $this->render('hello/index.html.twig', [
            'controller' => 'HelloController',
            'action' => 'other',
            'prev_action' => $action,
            'message' => $msg,
        ]);
    }
}
