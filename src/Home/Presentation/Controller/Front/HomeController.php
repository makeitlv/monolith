<?php

declare(strict_types=1);

namespace App\Home\Presentation\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: "/", name: "front.homepage")]
    public function __invoke(): Response
    {
        return $this->render("front/page/homepage/index.html.twig");
    }
}
