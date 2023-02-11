<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\OrderRepository;
use App\Repository\ProduitsRepository;
// use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


    /**
     * @Route("/api")
     */
class ApiProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_api_product", methods={"GET"})
     */
    public function index(ProduitsRepository $prodRepo, SerializerInterface $serializer){
        return $this->json($prodRepo->findAll(), 200, [], ['groups' => 'prods:read']);    
        }
        
    /**
     * @Route("/order", name="api_order", methods={"GET"})
     */
    public function getOrders(OrderRepository $orderRepo){
        return $this->json($orderRepo->findAll(), 200, [], ['groups'=>'orders:read']);
    }

    /**
     * @Route("/product/new", name="api_newProd", methods={"POST"})
     */
    public function addProduct (Request $req, SerializerInterface $serializer, CategoriesRepository $catRepo, EntityManagerInterface $em, ValidatorInterface $validator){
        try {

            $jsonData = $req->getContent();
            $prod = $serializer->deserialize ($jsonData, Produits::class, 'json');
            
            $cat = $prod->getCategorie();
            $getCat = $catRepo->findOneBy(['name'=>$cat->getName()]);
            //dd($cat, $getCat);
            


            //Mettre à jours la catégorie dans produit
            /*****************************************/
            $prod->setCategorie($getCat);
                //dd($prod);

            //Avant de persister il faut valider les données reçues pour
            /**********************************************************/
            $errors = $validator->validate($prod);
            if (count($errors)) {
                return $this->json($errors, 400);
                //pas besoin d'un else car le return permet de sortir de la fonction. Ca ne va pas persister s'il y a des erreurs.
            }
            //Enregistrer le produit dans la bdd
            /***********************************/
            $em->persist($prod);
            $em->flush();

            return $this->json($prod, 201, [], ['groups'=>'prods:read']);
            
        } catch (NotEncodableValueException $ex) {
                return $this->json(['status_Error'=>400, 'message'=>$ex->getMessage(), 400]);
        }
    }
}


//NotEncodableValueException ne fonctionne pas pas, status error et message ne s'affichent pas
//Mes categories ne s'affichent pas dans postman
//Tous les aspects de symfony:
//controller entity repo, docttrine pour les requete, authentification utilisateur, comment on vérouille les parties de notre code (isGranted, les rôles)
// fabrication d'une API sur notre système pour pouvoir donner accès aux données à d'autres applications que notre propres applications symfony

