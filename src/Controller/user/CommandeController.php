<?php

namespace App\Controller\user;


use App\Entity\Address;
use App\Entity\Commande;
use App\Entity\AddressOrder;
use App\Entity\CommandeDetail;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profile/order")
 */
class CommandeController extends AbstractController
{


    /**
     * @Route("/delivery", name="commande_delivery", methods={"GET"})
     */
    public function commandeDelivery(AddressRepository $addressRepository, Request $request){

        $myAddresses = $addressRepository->findBy(['user' => $this->getUser()->getId()]);

        $addressId=0;

        if(isset($_GET['form']['infoAddress'])){
            $addressId = $_GET['form']['infoAddress'];
        }
        
        $address = $addressRepository->find($addressId);

        $recherche = new AddressOrder();

        $form = $this->createFormBuilder($recherche, array('csrf_protection' => false))
                    ->add('infoAddress', EntityType::class, [
                            'class' => Address::class,
                            'label'=> false,
                            'query_builder' => function (AddressRepository $er) {
                                return $er->createQueryBuilder('u')
                                ->where('u.user = :user')
                                ->setParameter('user', $this->getUser()->getId())
                                ->orderBy('u.titre', 'ASC');
                            },
                            'choice_label' => 'titre'])
                    ->getForm();

        $form->handleRequest($request);

        return $this->render('commande/delivery.html.twig', [
            'myAddresses'=> $myAddresses,
            'form' =>$form->createView(),
            'addressId' => $addressId,
            'address'=> $address
        ]);
    }

    /**
     * @Route("/delivery/new", name="commande_deliveryNew", methods={"GET","POST"})
     */
    public function commandeDeliveryNew(AddressRepository $addressRepository, UserRepository $userRepository, Request $request){
        
        $user = $userRepository->find($this->getUser()->getId());

        $address = new Address();

        $form = $this->createFormBuilder($address)
                        ->add('titre', TextType::class, [
                            'label'=> 'Titre adresse'
                        ])
                        ->add('infoaddress', TextType::class, [
                            'label'=> 'Adresse'
                        ])
                        ->add('infoautre', TextType::class, [
                            'label'=> 'Bâtiment, étage, N° porte'
                        ])
                        ->add('postalCode', TextType::class, [
                            'label'=> 'Code postal'
                        ])
                        ->add('city', TextType::class, [
                            'label'=> 'Ville'
                        ])
                        ->add('phone', TextType::class, [
                            'label'=> 'Personne à contacter'
                        ])
                        ->add('submit', SubmitType::class, [
                            'label'=> 'Ajouter adresse'
                        ])
                        ->getForm();       

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user)
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime());
                    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('commande_delivery');
        }

        return $this->render('commande/newdelivery.html.twig', [
            // 'address' => $address,
            'form' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/new/{ship}", name="commande_new", methods={"GET","POST"})
     */
    public function new(CartService $cartService, AddressRepository $addressRepository, UserRepository $userRepository, Request $request, CommandeRepository $rep): Response
    {
        // je recupere l objet addresse par son id avec le get dans l'url nommé ship
        $address = $addressRepository->find($request->get('ship'));

        // je recupere mon panier
         $panierWithData = $cartService->getFullCart();

        // je recupere le total du panier
        $total = $cartService->getTotal($panierWithData);      

        // je recupere la derniere commande
        $lastCommande = $rep->findBy(array(), array('id' => 'desc'), 1, 0);

        // je recupere le nom de la derniere reference
        $reference = $lastCommande[0]->getRefCommande();

        // j'auto incremente la référence commande
        $reference +=1;  

        // je recuperer l'user en objet (connecté)
        $userObject = $userRepository->find($this->getUser()->getId());

        // je créé une nouvelle commande 
        $commande = new Commande();

        // j'insere les données dans l'objet commande
        $commande->setUser($userObject)
                 ->setRefCommande($reference)
                 ->setAddress($address)
                 ->setTotalCommande($total)
                 ->setCreatedAt(new \DateTime())
                 ->setUpdatedAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);

        // pour chaque ligne du panier, je creer le détailcommande
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
        // je pousse vers la db
        $entityManager->flush();

        // je vide le panier
        $cartService->deleteCart();

        // redirection vers la page profil
        return $this->redirectToRoute('profil_index');

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
