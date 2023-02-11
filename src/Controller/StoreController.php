<?php

namespace App\Controller;


use App\Form\ProdType;
use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('store/index.html.twig');
    }


    /**
     * @Route("/store/produit", name="prod")
     */
    public function produit(ProduitsRepository $prodRepo): Response
    {
        $produits = $prodRepo->findAll();
        return $this->render('store/produit.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/produit/new", name="prod-new")
     * @Route("/produit/edit/{id}", name="prod-edit")
     */
    public function addOrUpdateProduit(Produits $produit=NULL , Request $req, EntityManagerInterface $em){
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/error/accessErreur.html.twig');
		}
		else if (!$produit) {
            $produit = new Produits();
        }
        $formProd = $this->createForm(ProdType::class, $produit); //création formulaire

        $formProd->handleRequest($req); //permet de traiter la demande/faire la requete

        if ($formProd->isSubmitted() && $formProd->isValid()) {  //si le formulaire est envoyé et s'il est valide
            $em->persist($produit); //persiste prépare
            $em->flush(); //envoi dans la BDD

            return $this->redirectToRoute('prod', ['id' => $produit->getId()]); //renvoi vers la page liste des produits
        }
        return $this->render('produit/produitForm.html.twig',[
            'formProd' => $formProd->createView(),
            'mode' => $produit->getId() !== null  //permet de savoir si on est en mode creation ou modification. Indique si null alors pas d'id alors nouvel article
        ]);    
    }


    /**
     * @Route("/produit/delete/{id}", name="prod-delete")
     */
    public function deleteProduit(Produits $produit, EntityManagerInterface $em){
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/error/accessErreur.html.twig');
		}
		else if($produit){
            $em->remove($produit);//retire le produit remove
            $em->flush(); //validation du remove donc faire un flush
        }
        return $this->redirectToRoute('prod');
    }

}

