<?php

namespace App\Controller\user;


use App\Entity\Article;
use App\Entity\Catalog;
use App\Entity\ArticleSearchUser;
use App\Form\ArticleSearchUserType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profile/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator,ArticleRepository $articleRepository, Request $request): Response
    {

        $recherche = new ArticleSearchUser();

        $form = $this->createForm(ArticleSearchUserType::class, $recherche);

        $form->handleRequest(($request));

        $products = $paginator->paginate(
            $articleRepository->findArticleSearchUser($recherche),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('article/index.html.twig', [
            'articles' => $products,
            'recherche' => $recherche,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
                     ->add('reference', TextType::class, [
                         'label'=>'Titre'
                     ])
                     ->add('description')
                     ->add('catalog', EntityType::class, [
                         'label' => 'Catalogue',
                        'class' => Catalog::class,
                        'choice_label' => 'reference'])
                     ->add('price', MoneyType::class, [
                        'label'=>'Prix'
                     ])
                     ->add('imageFile', FileType::class, [
                         'label' =>'Image',
                        'required' => false
                     ])
                     ->add('save', SubmitType::class, ['label' => 'Créer article'])
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setCreatedUser($this->getUser()->getId())
                    ->setUpdatedUser($this->getUser()->getId());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ArticleRepository $repoArticle): Response
    {
        $article = $repoArticle->find($request->get('id'));

        $form = $this->createFormBuilder($article)
                     ->add('reference')
                     ->add('description')
                     ->add('catalog', EntityType::class, [
                        'class' => Catalog::class,
                        'choice_label' => 'reference'])
                     ->add('imageFile', FileType::class, [
                        'required' => false
                        ])
                    ->add('save', SubmitType::class, ['label' => 'Mettre à jour'])
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
