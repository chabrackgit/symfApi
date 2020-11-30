<?php

namespace App\Controller\user;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Service\Cart\CartService;
use App\Repository\CommandeRepository;
use App\Repository\EmployeeRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profile/order")
 */
class CommandeController extends AbstractController
{

    /**
     * @Route("/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(CartService $cartService,UserRepository $userRepository, CommandeRepository $rep): Response
    {
        $panierWithData = $cartService->getFullCart();

        $total = $cartService->getTotal($panierWithData);

        $lastCommande = $rep->findBy(array(), array('id' => 'desc'), 1, 0);

        $reference = $lastCommande[0]->getRefCommande();
        $refD = substr($reference, 0, 4);
        $ref = substr($reference,-1);
        $ref +=1;  

        $userObject = $userRepository->find($this->getUser()->getId());

        $commande = new Commande();

        $commande->setUser($userObject)
                 ->setRefCommande($refD.''.$ref)
                 ->setTotalCommande($total)
                 ->setCreatedAt(new \DateTime())
                 ->setUpdatedAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);

        for($i=0; $i < count($panierWithData); $i++){
            $commandeDetail[$i] = new CommandeDetail();

            $commandeDetail[$i]
                ->setCommande($commande)
                ->setArticle($panierWithData[$i]['article'])
                ->setQuantity($panierWithData[$i]['quantity'])
                ->setTotal($panierWithData[$i]['article']->getPrice()*$panierWithData[$i]['quantity']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commandeDetail[$i]);

        }

        $entityManager->flush();

        $cartService->deleteCart();

        return $this->redirectToRoute('commande_valide');
    }

    /**
     * @Route("/checked", name="commande_valide", methods={"GET"})
     */
    public function commandeValide(){

        $message= "Votre Commande est validée";
        return $this->render('commande/checked.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande, ArticleRepository $articleRepository): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }
}
