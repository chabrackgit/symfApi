<?php

namespace App\Controller\user;

use App\Repository\AddressRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommandeDetailRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/profile")
*/
class ProfilController extends AbstractController
{
    /**
    * @Route("/", name="profil_index")
    */
    public function index(ArticleRepository $articleRepository, CommandeDetailRepository $commandeDetailRepository, AddressRepository $addressRepository, CommandeRepository $commandeRepository): Response
    {
        $id = $this->getUser()->getId();

        $myArticles = $articleRepository->findBy(['createdUser' => $id]);
        $myCommandes = $commandeRepository->findBy(['user' => $id], ['id' => 'DESC']);
        $myAddresses = $addressRepository->findBy(['user' => $id]);

        return $this->render('profil/index.html.twig', [
            'myArticles'=> $myArticles,
            'myAddresses' => $myAddresses,
            'myCommandes' => $myCommandes
        ]);
    }
}
