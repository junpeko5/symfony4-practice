<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        $repository = $this->getDoctrine()
            ->getRepository(Message::class);
        $data = $repository->findAll();

        return $this->render('message/index.html.twig', [
            'title' => 'Message',
            'data' =>$data,
        ]);
    }


    /**
     * @Route("/message/create", name="message/create")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST') {
            $message = $form->getData();
            $errors = $validator->validate($message);
            if (count($errors) === 0) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($message);
                $manager->flush();
                return $this->redirect('/message');
            } else {
                $msg = "oh...can't posted...";
            }
        } else {
            $msg = 'type your message!';
        }
        return $this->render('message/create.html.twig', [
           'title' => 'Hello',
           'message' => $msg,
           'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/page/{page}", name="message/page")
     */
    public function page($page = 1)
    {
        $limit = 3;
        $repository = $this->getDoctrine()
            ->getRepository(Message::class);
        $paginator = $repository->getPage($page, $limit);
        $maxPages = ceil($paginator->count() / $limit);

        return $this->render('message/page.html.twig', [
           'title' => 'Message',
           'data' => $paginator->getIterator(),
            'maxPages' => $maxPages,
            'thisPage' => $page,
        ]);
    }
}
