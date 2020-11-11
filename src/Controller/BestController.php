<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleSearch;
use App\Form\ArticleSearchType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BestController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    
    /**
     * @Route("/products", name="products")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $recherche);

        $form->handleRequest($request);

        $products = $paginator->paginate(
            $this->articleRepository->findAllVisibleQuery($recherche),
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('best/index.html.twig', [
            'articles' => $products,
            'recherche'=> $recherche,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/{slug}-{id}", name="product.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Article $article, $slug)
    {
        if($article->getSlug() !== $slug){
            return $this->redirectToRoute('product.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ], 301);
        }

        return $this->render('best/show.html.twig', [
            'article' => $article
        ]);
    }
}
