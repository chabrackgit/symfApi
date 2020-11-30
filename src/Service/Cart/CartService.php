<?php

namespace App\Service\Cart;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $repo;

    public function __construct(SessionInterface $session, ArticleRepository $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }


    public function add(int $id){
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]= 1;
        }

        $this->session->set('panier', $panier);
    }

    public function inc(int $id){
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]= 1;
        }

        $this->session->set('panier', $panier);
    }

    public function dec(int $id){
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]--;
        }else{
            $panier[$id]= 1;
        }

        $this->session->set('panier', $panier);
    }

    public function remove(int $id){

        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);

    }

    public function getFullCart(): array {
        $panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach($panier as $id => $quantity){
            $panierWithData[] = [
                'article' => $this->repo->find($id),
                'quantity'=> $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal($val): float {

        $total = 0;

        foreach($val as $item){
            $totalItem = $item['article']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $total;

    }

    public function deleteCart() {

        $this->session->clear();

    }

}