<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Game;
use App\Form\ArticleType;
use App\Form\GameType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="app_game_index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository, CategoryRepository $categoryRepository, int $pageId = 1, Request $request): Response
    {

        $ram = $request->query->get('ram');
        $diskSpace = $request->query->get('diskspace');
        $selectedCategoryID = $request->query->get('category');
        $sortBy = $request->query->get('sort');
        $orderBy = $request->query->get('order');
        $selectedCategory = $categoryRepository->find((int)$selectedCategoryID);
        $gameRepo = $gameRepository;
        if (!is_null($selectedCategory) || !empty(($selectedCategory))) {
            foreach ($gameRepo->findAll() as $game) {
                if (!$game->Category->contains($selectedCategory)) {
                    $gameRepo->remove($game);
                }
            }
        }
        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        if (!is_null($ram) && !empty(($ram))) {
            $criteria->where($expressionBuilder->eq('Ram', $ram));
        }
        if (!is_null($diskSpace) && !empty(($diskSpace))) {
            $criteria->andWhere($expressionBuilder->eq('DiskSpace', $diskSpace));
        }
        if(!empty($sortBy)){
            $criteria->orderBy([$sortBy => ($orderBy == 'asc') ? Criteria::ASC : Criteria::DESC]);
        }
        $filteredList = $gameRepo->matching($criteria);

        $numOfItems = $filteredList->count();   // total number of items satisfied above query
        $itemsPerPage = 8; // number of items shown each page
        $filteredList = $filteredList->slice($itemsPerPage * ($pageId - 1), $itemsPerPage);
        $this->denyAccessUnlessGranted('ROLE_USER');
        $hasAccess = $this->isGranted('ROLE_USER');
        if ($hasAccess) {
            return $this->renderForm('game/index.html.twig', [
                'games' => $filteredList,
                'categories' => $categoryRepository ->findAll(),
                'category' => $selectedCategory,
                'selectedCat' => $selectedCategoryID,
                'numOfPages' => ceil($numOfItems/ $itemsPerPage),
            ]);
        } else {
            return $this->render('game/index.html.twig', [
                'games' => [],
                'categories' => $categoryRepository ->findAll(),
                'selectedCat' => $selectedCategoryID,
                'numOfPages' => ceil($numOfItems/ $itemsPerPage),
                'category' => $selectedCategory,
            ]);
        }

    }

    /**
     * @Route("/new", name="app_game_new", methods={"GET", "POST"})
     * @param Request $request
     * @param GameRepository $gameRepository
     * @param $slugger
     * @return Response
     */
    public function new(Request $request, GameRepository $gameRepository): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->add($game, true);
            $imageFile = $form->get('imgURL')->getData();
            if ($imageFile) {
                $newFilename = 'image'.$game->getId().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('game_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $game->setImgURL($newFilename);
            }
            $gameRepository->add($game, true);
            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_game_show", methods={"GET", "POST"})
     */
    public function show(Request $request,ArticleRepository $articleRepository,Game $game): Response
    {
       /* $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);*/
        $article1 = new Article();
        $form = $this->createForm(ArticleType::class,$article1);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article1->setGame($game);
            $articleRepository->add($article1, true);
            $imageFile = $form->get('ImgURLArticle')->getData();
            if ($imageFile) {
                $newFilename = 'image'.$game->getId().'article'.$article1->getId().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('article_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $article1->setImgURLArticle($newFilename);
            }
            $articleRepository->add($article1, true);
            return $this->redirectToRoute('app_game_show', ['id'=>(int)($game->getId())], Response::HTTP_SEE_OTHER);
        }
        /*$form = $this->createForm(ArticleType::class, $article);


        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->add($article, true);

            if ($form->isSubmitted() && $form->isValid()) {
                $articleRepository->add($article, true);
                 return $this->redirectToRoute('app_game_show', ['id'=>(int)($game->getId())], Response::HTTP_SEE_OTHER);
            }
        }*/

        return $this->renderForm('game/show.html.twig', [
          /*  'form' => $form,*/
            'game' => $game,
           /* 'forms' => $forms,*/
            'form' => $form,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="app_game_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imgURL')->getData();
            if ($imageFile) {
                $newFilename = 'image'.$game->getId().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('game_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $game->setImgURL($newFilename);
            }
            $gameRepository->add($game, true);

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_game_delete", methods={"POST"})
     */
    public function delete(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $gameRepository->remove($game, true);
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }
}
