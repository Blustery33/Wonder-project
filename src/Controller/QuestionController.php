<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Form\CommentType;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question->setNbrOfResponse(0);
            $question->setRating(0);
            $question->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($question);
            $entityManager->flush();
            $this->addFlash('success', 'Votre question a été ajouté');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('question/index.html.twig', [
            'form' => $formQuestion,
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {

        $questions = [
            'title' => 'Je suis une question',
            'content' => "Je suis actuellement en train d'explorer le domaine de l'apprentissage automatique et je me demande quels sont les avantages et les inconvénients de l'apprentissage automatique supervisé par rapport à l'apprentissage non supervisé. J'ai une compréhension de base des deux concepts, mais j'aimerais approfondir ma connaissance afin de mieux comprendre dans quelles situations chaque approche est la plus appropriée.",
            'rating' => 0,
            'author' => [
                'name' => 'Jean dupont',
                'avatar' => 'https://www.fakepersongenerator.com/Face/male/male20161083875947113.jpg'
            ],
            'nbrOfResponse' => 15
        ];


        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setRating(0);
            $comment->setQuestion($question);
            $question->setNbrOfResponse($question->getNbrOfResponse() + 1);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre réponse a bien été ajouté');
            return $this->redirect($request->getUri());
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'form' => $commentForm
        ]);
    }

    #[Route('/question/{id}/{score}', name: 'question_rating')]
    public function ratingQuestion(Request $request, Question $question, int $score ,EntityManagerInterface $entityManager): Response
    {
        $question->setRating($question->getRating() + $score);
        $entityManager->flush();

        $referer = $request->headers->get('HTTP_REFERER');
        return $referer ? $this->redirectToRoute($referer) : $this->redirectToRoute('app_home');

    }

    #[Route('/comment/{id}/{score}', name: 'comment_rating')]
    public function ratingComment(Request $request, Comment $comment, int $score ,EntityManagerInterface $entityManager): Response
    {
        $comment->setRating($comment->getRating() + $score);
        $entityManager->flush();

        $referer = $request->headers->get('HTTP_REFERER');
        return $referer ? $this->redirectToRoute($referer) : $this->redirectToRoute('app_home');

    }
}
