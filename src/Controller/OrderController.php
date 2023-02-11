<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Orderline;
use App\Service\Cart\CartService;
use App\Repository\OrderRepository;
use App\Repository\OrderlineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/order")
 */

class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_all")
     */
    public function index(OrderRepository $orderRepo): Response
    {

        return $this->render('order/index.html.twig', [
            'orders' => $orderRepo->findAll() //demande au repo de redonner toutes les ocmmandes qui sont dans la table des commandes
        ]);
    }

    /**
     * @Route("/add/{user}", name="order_add")
     */

    public function addOrder(User $user=null, CartService $cart, EntityManagerInterface $em){  //entity pour pouvoir faire un create dans BDD
       
       if($user){
           $order = new Order();
           $order->setRefCde("CDE_.XXXXXX");
           $order->setDate(new \DateTimeImmutable());
           $order->setTotal($cart->getCartTotal());
           $order->setCustomer($user);
   
           $em->persist($order);

           //Creation des lignes de commande CDE:
           //***********************************/
           $cartDetails = $cart->getCartDetails();

           foreach ($cartDetails as $line){
                $orderline = new Orderline();
                $orderline->setQuantity($line['qty']);
                $orderline->setProduct($line['produit']);
                $orderline->setOrdernum($order);

                $totalLigne=$line['qty'] * $line['produit']->getUnitPrice();
                
                $orderline->setTotal($totalLigne);

                $em->persist($orderline);
           }
           $em->flush(); 
           $cart->clearCart(); //pour vider le panier
           return $this->redirectToRoute('order_all');
       }else{
           return $this->redirectToRoute('home');
       }
    }

    //Detail de la commande
    /**
     * @Route("/detail/{order}", name="order_detail")
     */
    public function showCdeDetail(Order $order, OrderlineRepository $olRepo){
        
        return $this->render('/order/orderDetail.html.twig', [
            'ols' => $order->getOrderLines()
        ]);
        
    }
}
