<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle;

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


    /**
     * @Route("/find", name="find")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function find(Request $request)
    {
        $formObj = new FindForm();
        $form = $this->createFormBuilder($formObj)
            ->add('find', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();

        $repository = $this->getDoctrine()->getRepository(Person::class);

        $manager = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $findStr = $form->getData()->getFind();
            $query = $manager->createQuery("SELECT p FROM App\Entity\Person p WHERE p.name = '{$findStr}'");
            $result = $query->getResult();
        } else {
            $result = $repository->findAllWithSort();
        }
        return $this->render('hello/find.html.twig', [
           'title' => 'Hello',
           'form' => $form->createView(),
            'data' => $result,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST') {
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($person);
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Create Entity',
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/update/{id}", name="update")
     * @param Request $request
     * @param Person $person
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('/hello/create.html.twig', [
               'title' => 'Hello',
               'message' => 'Update Entity id=' . $person->getId(),
               'form' => $form->createView(),
            ]);
        }
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @param Request $request
     * @param Person $person
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($person);
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('/hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Delete Entity id=' . $person->getId(),
                'form' => $form->createView()
            ]);
        }
    }
}

class FindForm
{
    private $find;

    public function getFind()
    {
        return $this->find;
    }

    public function setFind($find)
    {
        $this->find = $find;
    }
}
