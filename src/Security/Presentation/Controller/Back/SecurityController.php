<?php

declare(strict_types=1);

namespace App\Security\Presentation\Controller\Back;

use App\Security\Infrastructure\Provider\Back\AdminIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use RuntimeException;

class SecurityController extends AbstractController
{
    #[Route("/admin/auth/login", name: "back.login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof AdminIdentity) {
            return $this->redirectToRoute("back.dashboard");
        }

        return $this->render("back/page/auth/login.html.twig", [
            "error" => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route("/admin/auth/logout", name: "back.logout")]
    public function logout(): void
    {
        throw new RuntimeException("Don't forget to activate logout in security.yaml");
    }
}
