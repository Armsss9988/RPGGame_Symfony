<?php

namespace App\Controller;

use App\Entity\GameCategory;
use App\Form\GameCategoryType;
use App\Repository\GameCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categoryGame")
 */
class GameCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_game_category_index", methods={"GET"})
     */
    public function index(GameCategoryRepository $gameCategoryRepository): Response
    {
        return $this->render('game_category/index.html.twig', [
            'game_categories' => $gameCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_game_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GameCategoryRepository $gameCategoryRepository): Response
    {
        $gameCategory = new GameCategory();
        $form = $this->createForm(GameCategoryType::class, $gameCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameCategoryRepository->add($gameCategory, true);

            return $this->redirectToRoute('app_game_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game_category/new.html.twig', [
            'game_category' => $gameCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_game_category_show", methods={"GET"})
     */
    public function show(GameCategory $gameCategory): Response
    {
        return $this->render('game_category/show.html.twig', [
            'game_category' => $gameCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_game_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, GameCategory $gameCategory, GameCategoryRepository $gameCategoryRepository): Response
    {
        $form = $this->createForm(GameCategoryType::class, $gameCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameCategoryRepository->add($gameCategory, true);

            return $this->redirectToRoute('app_game_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game_category/edit.html.twig', [
            'game_category' => $gameCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_game_category_delete", methods={"POST"})
     */
    public function delete(Request $request, GameCategory $gameCategory, GameCategoryRepository $gameCategoryRepository): Response
    {
        $game = $gameCategory->getGame();
        if ($this->isCsrfTokenValid('delete'.$gameCategory->getId(), $request->request->get('_token'))) {
            $gameCategoryRepository->remove($gameCategory, true);
        }
        return $this->redirectToRoute('app_game_show', ['id' => (int)($game->getId())], Response::HTTP_SEE_OTHER);
    }
}
