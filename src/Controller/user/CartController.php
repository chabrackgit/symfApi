<?php

namespace App\Controller\user;

use App\Entity\Address;
use App\Entity\AddressOrder;
use App\Service\Cart\CartService;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/profile/panier")
 */
class CartController extends AbstractController
{

    /**
     * @Route("/", name="cart_index")
     */
    public function index(Request $request, CartService $cartService) 
    {
        
        $panierWithData = $cartService->getFullCart();

        $total = $cartService->getTotal($panierWithData);

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total

        ]);  
    }

    /**
     * @Route("/test", name="cart_test")
     */
    public function indextest(Request $request, SessionInterface $session) 
    {
        $panier = $session->get('panier', []);

        return $this->render('cart/test.html.twig', [
            'panier' => $panier
        ]);  
    }

    /**
     * add
     *
     * @route("/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService){
      
        $cartService->add($id);
        $this->addFlash('info', 'Article ajouté');
        return $this->redirectToRoute("products");

    }

    /**
     * add
     *
     * @route("/inc/{id}", name="cart_inc")
     */
    public function inc($id, CartService $cartService){
      
        $cartService->inc($id);
        $this->addFlash('success', 'Article ajouté');
        return $this->redirectToRoute("cart_index");

    }

    /**
     * dec
     *
     * @route("/dec/{id}", name="cart_dec")
     */
    public function dec($id, CartService $cartService){
      
        $cartService->dec($id);
        $this->addFlash('success', 'Article retiré');
        return $this->redirectToRoute("cart_index");

    }

    /**
     * remove
     *
     * @route("/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService){
        
        $cartService->remove($id);
        return $this->redirectToRoute(("cart_index"));
    }

    /**
     * panierv2
     *
     * @route("/v2", name="panierv2")
     */
    public function testouille(Request $request, CartService $cartService){

        $panierWithData = $cartService->getFullCart();

        $total = $cartService->getTotal($panierWithData);



        // $allo="";
        // $addressId= "1";
        // if(isset($_POST['form'])){
        //     $allo ="connard";
        //     $addressId = $_POST['form']['infoAddress'] ;
        // }
        
        return $this->render('cart/panier.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            // 'myAddresses' => $myAddresses,
            // 'form' => $form->createView(),
            // 'allo'=> $allo,
            // 'addressId'=> $addressId
        ]);
    }

}
