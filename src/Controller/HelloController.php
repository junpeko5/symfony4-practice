<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
  /**
   * @Route("/hello", name="hello")
   */
    public function index()
    {
        $result = <<< EOM
<html>
<head>
<title>Hello Symfony!</title>
</head>
<body>
<p>this is Symfony sample page.</p>
</body>
</html>
EOM;
        return new Response($result);

    }
}
