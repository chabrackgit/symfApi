<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("/", name="address_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="address_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
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

            return $this->redirectToRoute('profil_index');
        }
        
        return $this->render('commande/newdelivery.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Address $address): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Address $address): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if($this->isCsrfTokenValid('delete'. $address->getId(), $data['_token'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();
            return new JsonResponse(['success'=> 1]);

        }

        return new JsonResponse(['error' => 'Token invalide'], 400);

        return $this->redirectToRoute('profil_index');
    }
}
