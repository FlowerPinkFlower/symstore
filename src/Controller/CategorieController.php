<?php

namespace App\Controller;

use App\Form\CateType;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    //READ
    /**
     * @Route("/categorie", name="cate")
     */
    public function showCategorie(CategoriesRepository $cateRepo): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/error/accessErreur.html.twig');
		}
		else {
        $categories = $cateRepo->findAll();
        return $this->render('categorie/categorie.html.twig', [
            'categories' => $categories
        ]);
    }
}

    /**
     * @Route("/categorie/new", name="cate-new")
     * @Route("/categorie/edit/{id}", name="cate-edit")
     */
    public function addOrUpdateCategorie(Categories $categorie=NULL , Request $req, EntityManagerInterface $em){
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/error/accessErreur.html.twig');
		}
            else if (!$categorie) {
                $categorie = new Categories();
            }
            $formCate = $this->createForm(CateType::class, $categorie);

            $formCate->handleRequest($req); //permet de faire la requete

        if ($formCate->isSubmitted() && $formCate->isValid()) {  //si le formulaire est envoyé et s'il est valide
            $em->persist($categorie); //persiste prépare
            $em->flush(); //envoi dans la BDD

            return $this->redirectToRoute('cate'); //renvoi vers la page liste des categories
        }
        return $this->render('categorie/categorieForm.html.twig',[
            'formCate' => $formCate->createView(),
            'mode' => $categorie->getId() !== null  //permet de savoir si on est en mode creation ou modification. Indique si null alors pas d'id alors nouvel article
        ]);    
    }
    
    /**
     * @Route("/categorie/delete/{id}", name="cate-delete")
     */
    public function deleteCategorie(Categories $categorie, EntityManagerInterface $em){
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/error/accessErreur.html.twig');
		}
		else if($categorie){
            $em->remove($categorie);
            $em->flush();
        }
        return $this->redirectToRoute('cate');
    }

}
