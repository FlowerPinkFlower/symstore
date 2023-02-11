<?php

namespace App\Controller;


use App\Service\Cart\CartService;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//Définission de la route CartController pour ne pas écrire des routes à rallonges pour les fonctions.

/**
 * @Route("/cart")
 */

class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart")
     */
    public function index(SessionInterface $session, CartService $cart): Response
    {



        return $this->render('/cart/index.html.twig', [
            'cartDetails' => $cart->getCartDetails(),
            'totalPanier' => $cart->getCartTotal()
            
        ]);
    }

    /**
     * @Route("/add/{idProd}", name="cart_add")
     */

     public function addProduct(int $idProd, CartService $cartService){ 

        $cartService->add($idProd);

        return $this->redirectToRoute('prod');
    }   


    /**
     * @Route("/deleteProduct/{idProd}", name="cart_del")
     */

     public function delProduct($idProd, CartService $cartService){
        $cartService->deleteProduct($idProd);

        return $this->redirectToRoute('cart');
    }


    /**
     * @Route("/decQty/{idProd}", name="qty_dec")
     */
    public function decrementationQty($idProd, CartService $cartService){
        $cartService->decQty($idProd);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/up/{idProd}", name="qty_up")
     */
    public function upQty($idProd, CartService $cartService){
        $cartService->add($idProd);

        return $this->redirectToRoute('cart');
    }

}