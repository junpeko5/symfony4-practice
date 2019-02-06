<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
        $finder = new Finder();
        $finder->files()->path('var/log')->name('dev.log')->in('../');
        $file = null;
        foreach($finder as $item) {
            $file = $item;
            break;
        }
        return $this->render('hello/hello.html.twig', [
           'title' => 'Hello',
           'message' => 'get file/folder',
           'file' => $file,
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

        $mapping = new ResultSetMappingBuilder($manager);
        $mapping->addRootEntityFromClassMetadata('App\Entity\Person', 'p');

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $findStr = $form->getData()->getFind();
            $arr = explode(',', $findStr);
            $query = $manager->createNativeQuery('SELECT * FROM person WHERE age between ?1 AND ?2', $mapping)
                ->setParameters([1 => $arr[0], 2 => $arr[1]]);
            $result = $query->getResult();
        } else {
            $query = $manager->createNativeQuery('SELECT * FROM person', $mapping);
            $result = $query->getResult();
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
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request ,ValidatorInterface $validator)
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Assert\Length([
                            'min' => 3,
                            'max' => 10,
                            'minMessage' => '3文字以上必要です。',
                            'maxMessage' => '10文字以内にしてください。'
                        ])
                    ]
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $msg = 'Hello, '. $form->get('name')->getData() . '!';
            } else {
                $msg = 'Error!';
            }
        } else {
            $msg = 'Send Form';
        }
        return $this->render('hello/create.html.twig' , [
            'title' => 'Hello',
            'message' => $msg,
            'form' => $form->createView()
        ]);

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
