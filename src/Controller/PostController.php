<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    #[Route('/posts', name: 'panel.posts.index', methods: ['GET'])]
    public function index(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();
        return $this->render('post/index.html.twig', compact('posts'));
    }
}
