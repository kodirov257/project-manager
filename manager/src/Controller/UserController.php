<?php

namespace App\Controller;

use App\Model\User\Entity\User\User;
use App\Model\User\UseCase\Create;
use App\ReadModel\User\UserFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[Route('', name: 'users')]
    public function index(Request $request, UserFetcher $users): Response
    {
        $users = $users->all();

        return $this->render('app/users/index.html.twig', compact('users'));
    }

    #[Route('/create', name: 'users.create')]
    public function create(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('users');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'users.show')]
    public function show(User $user): Response
    {
        return $this->render('app/users/show.html.twig', compact('user'));
    }
}