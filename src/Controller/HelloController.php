<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class HelloController extends AbstractController
{
    /**
     * @Route("/index", name="index")
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
            'leader' => '<h1>見出し</h1>',
            'upper' => 'Upp,er',
            'minus' => -1000,
            'now' => date('Y-m-d'),
            'arr' => ['apple'],
            'num' => 1.32323,
            'nums' => [1,4,2,4,6,7,8,9],
            'url' => 'http://127.0.0.1:8001/hello'
        ]);
    }


    /**
     * @Route("/hello", name="hello")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hello(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Person::class);
        $data = $repository->findAll();
        return $this->render('hello/hello.html.twig', [
           'title' => 'Hello',
           'data' => $data,
        ]);
    }
}
