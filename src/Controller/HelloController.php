<?php

namespace App\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HelloController extends AbstractController
{
  /**
   * @Route("/hello", name="hello")
   */
    public function index(Request $request)
    {
        $content = <<< EOM
<html>
<head>
<title>Hello</title>
</head>
<body>
<p>This is Symfony sample page.</p>
</body>
</html>
EOM;
        $response = new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
        return $response;
    }


    /**
     * @Route("/notfound", name="notfound")
     * @param Request $request
     * @return Response
     */
    public function notfound(Request $request)
    {
        $content = <<< EOM
<html>
<head>
<title>NotFound 404</title>
</head>
<body>
<p>Not found</p>
</body>
</html>
EOM;
        $response = new Response(
            $content,
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'text/html']
        );
        return $response;
    }

    /**
     * @Route("/error", name="error")
     * @param Request $request
     * @return Response
     */
    public function error(Request $request)
    {
        $content = <<< EOM
<html>
<head>
<title>Error</title>
</head>
<body>
<h1>Error! 500</h1>
</body>
</html>
EOM;
        $response = new Response(
            $content,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            ['content-type' => 'text/html']
        );
        return $response;
    }
}
