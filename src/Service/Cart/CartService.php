<?php

namespace App\Service\Cart;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;




class CartService{
    protected $sessions;
    public function __construct(SessionInterface $sessions, ProduitsRepository $prodRepo){
        $this->session = $sessions;
        $this->prodRepo = $prodRepo;
    }

    public function add(int $idProd){ 
            
        $panier = $this->session->get('panier', []); 

        if (!empty($panier[$idProd])) {
            $panier[$idProd]++; 
        }
        else{
            $panier[$idProd] = 1;
        }
        $this->session->set('panier', $panier);
    }

    public function getCartDetails(): array {  //permet d'avoir le détail de la commande
        $panier = $this->session->get('panier',[]);
        $cartDetails = [];

        foreach ($panier as $prodId => $qty) {
            $cartDetails[]= [
                'produit' => $this->prodRepo->find($prodId),
                'qty' => $qty
            ];
        }
        return $cartDetails;
    }


    // $panier = $session->get('panier', []);

    // $cartDetails = [];
    //     foreach($panier as $prodId => $qty){
    //         $cartDetails [] = [
    //         'produit' => $prodRepo->find($prodId),
    //         'qty' => $qty

    //         ];
    //     }



    public function getCartTotal(): float{ //permet de mettre à jour le total de la commande
    
    $totalPanier=0;

        foreach ($this->getCartDetails() as $line) {
            $totalLine = $line['produit']->getUnitPrice() * $line['qty'];
            $totalPanier += $totalLine;
        }
    return $totalPanier;

    }

    public function getCartQty():int {
        $totalQty=0;

        $panier = $this->session->get('panier', []);

        foreach($panier as $id => $qty){
            $totalQty += $qty;
        }
        return $totalQty;
    }


//Supprimer une ligne du panier
    public function deleteProduct(int $idProd){
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$idProd])) {  //vérifie si tableau est vide ou pas. C'est pour fair un test
            unset($panier[$idProd]);    //unset supprime
        }

        $this->session->set('panier', $panier);
    }
    

//Décrementer une quantité
    public function decQty(int $idProd){
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$idProd]) && ($panier[$idProd])>1) {
            $panier[$idProd]--;
        }else{
            unset($panier[$idProd]);
        }

        $this->session->set('panier', $panier);
    }


//Nettoie le panier
    public function clearCart(){
        $this->session->remove('panier');
    }
}


?>