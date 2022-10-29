<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Game;
use App\Entity\GameCategory;
use App\Entity\Link;
use App\Form\ArticleType;
use App\Form\GameCategoryType;
use App\Form\GameType;
use App\Form\LinkType;
use App\ImageOptimizer;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use App\Repository\LinkRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Intervention\Image\Image;
use Symfony\Bridge\Doctrine\ManagerRegistry;
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
     * @Route("/{page<\d+>}", name="app_game_index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository, CategoryRepository $categoryRepository, $page = 1, Request $request): Response
    {
        $ram = $request->query->get('ram');
        $diskSpace = $request->query->get('diskspace');
        $selectedCategoryID = $request->query->get('categoryID');
        $selectedCategory = $categoryRepository->find((int)$selectedCategoryID);
        $sortBy = $request->query->get('sort');
        $orderBy = $request->query->get('order');
        $gameSearch = $request->query->get('gameName');
        $views = $request->query->get('views');

        /* $gameRepo = $gameRepository;
        if (!is_null($selectedCategory) || !empty(($selectedCategory))) {
            foreach ($gameRepo->findAll() as $game) {
                if (!$game->Category->contains($selectedCategory)) {
                    $gameRepo->remove($game);
                }
            }
        }*/
        /*$expressionBuilder = Criteria::expr();
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
        $filteredList = $filteredList->slice($itemsPerPage * ($pageId - 1), $itemsPerPage);*/
        $tempQuery = $gameRepository->Filter($ram, $diskSpace, $selectedCategoryID, $sortBy, $orderBy, $gameSearch, $views);
        $pageSize = 4;

// load doctrine Paginator
        $paginator = new Paginator($tempQuery);

// you can get total items
        $totalItems = count($paginator);

// get total pages
        $numOfPages = ceil($totalItems / $pageSize);

// now get one page's items:
        $tempQuery = $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit
        $hasAccess = $this->isGranted('ROLE_USER');
        if ($hasAccess) {
            return $this->renderForm('game/index.html.twig', [
                'games' => $tempQuery->getResult(),
                'categories' => $categoryRepository->findAll(),
                'category' => $selectedCategory,
                'selectedCat' => $selectedCategoryID,
                'numOfPages' => $numOfPages,
            ]);
        } else {
            return $this->render('game/index.html.twig', [
                'games' => $tempQuery->getResult(),
                'categories' => $categoryRepository->findAll(),
                'selectedCat' => $selectedCategoryID,
                'numOfPages' => $numOfPages,
                'category' => $selectedCategory,
            ]);
        }

    }

    /**
     * @Route("/gamedashboard", name="app_game_dashboard", methods={"GET"})
     */
    public function game_dashboard(GameRepository $gameRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('game/game_dashboard.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    /**
     * @Route("/usersdashboard", name="app_user_dashboard", methods={"GET"})
     */
    public function user_dashboard(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user/user.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/form", name="app_game_new", methods={"GET", "POST"})
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
            $game->setViews(1);
            $gameRepository->add($game, true);
            return $this->extracted($form, $game, $gameRepository);

        }

        return $this->renderForm('game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_game_show", methods={"GET", "POST"})
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function show(Request $request, LinkRepository $linkRepository, Game $game, ArticleRepository $articleRepository, GameCategoryRepository $gameCategoryRepository, GameRepository $gameRepository): Response
    {
        if ($game->getViews() == null) {
            $game->setViews(1);
            $gameRepository->add($game, true);
        } else {
            $views = $game->getViews() + 1;
            $game->setViews($views);
            $gameRepository->add($game, true);
        }
        $gameCategory = new GameCategory();
        $formCategory = $this->createForm(GameCategoryType::class, $gameCategory);
        $formCategory->handleRequest($request);
        $duplicate = 0;
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            if ($game->getGameCategories() != null) {
                foreach ($game->getGameCategories() as $gameCat) {
                    if ($gameCat->getCategory() === $gameCategory->getCategory()) {
                        $duplicate += 1;
                    }
                }
                if ($duplicate == 0) {
                    $gameCategory->setGame($game);
                    $gameCategoryRepository->add($gameCategory, true);
                    return $this->redirectToRoute('app_game_show', ['id' => (int)($game->getId())], Response::HTTP_SEE_OTHER);
                }
            }
        }
        if ($game->getArticle() == null) {
            $article = new Article();
        } else {
            $article = $game->getArticle();
        }
        $formArticle = $this->createForm(ArticleType::class, $article);
        $formArticle->handleRequest($request);

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $article->setGame($game);
            $articleRepository->add($article, true);
            return $this->redirectToRoute('app_game_show', ['id' => (int)($game->getId())], Response::HTTP_SEE_OTHER);
        }
        $link = new Link();
        $formLink = $this->createForm(LinkType::class, $link);
        $formLink->handleRequest($request);

        if ($formLink->isSubmitted() && $formLink->isValid()) {
            $link->setGame($game);
            $linkRepository->add($link, true);
            return $this->redirectToRoute('app_game_show', ['id' => (int)($game->getId())], Response::HTTP_SEE_OTHER);
        }
        /*$article1 = new Article();
        $formArticle = $this->createForm(ArticleType::class,$article1);
        $formArticle->handleRequest($request);
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $article1->setGame($game);
            $articleRepository->add($article1, true);
            $imageFile = $formArticle->get('ImgURLArticle')->getData();
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
        }*/
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
            'formArticle' => $formArticle,
            'formLink' => $formLink,
            'article' => $article,
            'links' => $game->getLinks(),
            'formCategory' => $formCategory,
            'gameTypes' => $game->getGameCategories(),
        ]);

    }

    /**
     * @Route("/{id}/edit", name="app_game_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->extracted($form, $game, $gameRepository);
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $gameRepository->remove($game, true);
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param Game $game
     * @param GameRepository $gameRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function extracted(\Symfony\Component\Form\FormInterface $form, Game $game, GameRepository $gameRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $imageFile = $form->get('imgURL')->getData();
        if ($imageFile) {
            $newFilename = 'image' . $game->getId() . '.' . $imageFile->guessExtension();
            try {
                // Retrieve image resolution and aspect ratio.
                list($width, $height) = getimagesize($imageFile);
                $aspectRatio = $width / $height;
                $im = imagecreatefromjpeg($imageFile);
                // Crop the image based on the provided aspect ratio.
                if ($aspectRatio !== 1) {
                    $portrait = $aspectRatio < 1;

                    // This will check if the image is portrait or landscape and crop it square accordingly.
                    $im2 = imagecrop($im, [
                        'x' => $portrait ? 0 : (($width - $height) / 2),
                        'y' => $portrait ? (($width - $height) / 2) : 0,
                        'width' => $portrait ? $width : $height,
                        'height' => $portrait ? $width : $height
                    ]);
                    imagejpeg($im2,'image/game/'.$newFilename);
                    imagedestroy($im2);
                }
                $imageFile->move(
                    $this->getParameter('game_directory'),
                    'raw'.$newFilename
                );
            } catch (FileException $e) {
            }
            $game->setImgURL($newFilename);
        }
        $gameRepository->add($game, true);

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);


    }


}
