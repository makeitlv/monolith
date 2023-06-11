<?php

declare(strict_types=1);

namespace App\Security\Presentation\Controller\Back;

use App\Security\Infrastructure\Adapter\Admin\AdminAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ConfirmationController extends AbstractController
{
    public function __construct(private AdminAdapter $adminAdapter)
    {
    }

    #[Route("/admin/auth/confirm/{token}", name: "back.confirm")]
    public function confirm(string $token): Response
    {
        try {
            $this->adminAdapter->activateAdmin($token);

            $confirmed = true;
        } catch (Throwable $e) {
            $confirmed = false;
        }

        return $this->render("back/page/auth/confirmation.html.twig", [
            "confirmed" => $confirmed,
        ]);
    }
}
