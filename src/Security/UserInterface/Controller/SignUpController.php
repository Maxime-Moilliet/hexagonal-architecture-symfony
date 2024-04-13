<?php

declare(strict_types=1);

namespace App\Security\UserInterface\Controller;

use App\Core\Domain\CQRS\CommandBus;
use App\Security\Domain\UseCase\SignUp\NewUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sign-up', name: 'sign_up', methods: [Request::METHOD_POST])]
final class SignUpController extends AbstractController
{
    public function __invoke(NewUserCommand $newUserCommand, CommandBus $commandBus): Response
    {
        $commandBus->execute($newUserCommand);

        return new Response('', Response::HTTP_CREATED);
    }
}
