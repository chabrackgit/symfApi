<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\ArticleSearch;
use App\Form\ArticleSearchType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Notification\ContactNotification;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BestController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, CommentRepository $commentRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
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
     * @Route("/products/{slug}-{id}", name="product.show", methods={"GET","POST"}, requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Article $article, $slug, Request $request, ContactNotification $notification)
    {

        if($article->getSlug() !== $slug){
            return $this->redirectToRoute('product.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ], 301);
        }

        $vendor = $this->userRepository->find($article->getCreatedUser());

        // partie Commentaire (ajouter)
        $comment = new Comment();

        // partie nouveau Contact;
        $contact = new Contact();
        $contact->setArticle($article);


        // traitement du contact vendeur 
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
            // envoi d 'email au possesseur de l'article
            $notification->notify($contact, $vendor->getEmail());
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('product.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ]);
        }

        // traitement des commentaires 
        $form = $this->createFormBuilder($comment)
                    ->add('titre', TextType::class, [
                        'label' => 'objet'
                    ])
                    ->add('infotext', TextareaType::class, [
                        'label' => 'description'
                    ])
                    ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article)
                    ->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        // liste de tous les commentaires de cet article
        $comments = $this->commentRepository->findBy(['article' => $article->getId()]);
        

        return $this->render('best/show.html.twig', [
            'article' => $article,
            'vendor' => $vendor,
            'comments'=> $comments,
            'form' => $form->createView(),
            'formContact' => $formContact->createView(),

        ]);
    }
}
