<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\GameRepository;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/link")
 */
class LinkController extends AbstractController
{

    /**
     * @Route("/", name="app_link_index", methods={"GET"})
     */
    public function index(LinkRepository $linkRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('link/index.html.twig', [
            'links' => $linkRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_link_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LinkRepository $linkRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $linkRepository->add($link, true);

            return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('link/new.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_link_show", methods={"GET"})
     */
    public function show(Link $link): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    /**
     * @Route("/{id}/edit/{game_id}", name="app_link_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Link $link,GameRepository $gameRepository, LinkRepository $linkRepository): Response
    {
        $game_id = $request->attributes->get('game_id');
        $game = $gameRepository->find($game_id);
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setGame($game);
            $linkRepository->add($link, true);

            return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('link/edit.html.twig', [
            'link' => $link,
            'form' => $form,
            'game_id' => $game_id
        ]);
    }

    /**
     * @Route("/{id}", name="app_link_delete", methods={"POST"})
     */
    public function delete(Request $request, Link $link, LinkRepository $linkRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $game = $link->getGame();
        if ($this->isCsrfTokenValid('delete'.$link->getId(), $request->request->get('_token'))) {
            $linkRepository->remove($link, true);
        }

        return $this->redirectToRoute('app_game_show', ['id'=>(int)($game->getId())], Response::HTTP_SEE_OTHER);
    }
}
