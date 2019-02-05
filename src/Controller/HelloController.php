<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        $data = [
            ['name' => 'Taro', 'age' => 37, 'mail' => 'taro@yamada'],
            ['name' => 'Hanako', 'age' => 29, 'mail' => 'hanako@flower'],
            ['name' => 'Sachiko', 'age' => 43, 'mail' => 'sachico@happy'],
            ['name' => 'Jiro', 'age' => 18, 'mail' => 'jiro@change'],
        ];
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
            'leader' => '<h1>見出し</h1>'
        ]);
    }
}
