<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(QuestionRepository $questionsRepository): Response
    {
        //'avatar' => 'https://www.fakepersongenerator.com/Face/male/male20161083875947113.jpg'

        $questions = $questionsRepository->findAll();
        return $this->render('home/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
