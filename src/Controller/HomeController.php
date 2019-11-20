<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {

        $repersitory=$this->getDoctrine()->getRepository(Produit::class);
        $produits=$repersitory->findAll();

        return $this->render('home/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/home/add", name="monForm")
     */
    public function addForm(Request $request)
    {
        //je créer un objet categorie vide
        $produit = new Produit();

        //créer le form
        $formulaire = $this->createForm(ProduitType::class, $produit);

        //récupérer les données du POST
        $formulaire->handleRequest($request);



            if ($formulaire->isSubmitted() && $formulaire->isValid()) {
                //récupération de l'entity manager
                $em = $this->getDoctrine()->getManager();

                $em->persist($produit);
                $em->flush();

                return $this->redirectToRoute("home");
            }

        return $this->render('home/formulaire.html.twig', [
            "formulaire" => $formulaire->createView()
            , "h1" => "Ajouter un produit"
        ]);
    }


    /**
     * @Route("/home/modifier/{id}", name="monForm_modif")
     */
    public function modifier(Request $request, $id){
        $repersitory=$this->getDoctrine()->getRepository(Produit::class);
        $produit=$repersitory->find($id);
        $formulaire=$this->createForm(ProduitType::class, $produit);


        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()){

            $em=$this->getDoctrine()->getManager();

            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute("home");
        }


        return $this->render('home/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView()
            ,"h1"=>"modifier le produit ".$produit->getNom()
        ]);


    }



    /**
     * @Route("/home/supprimer/{id}", name="suppr")
     */
    public function supprimer(Request $request, $id){
        $repersitory=$this->getDoctrine()->getRepository(Produit::class);
        $produit=$repersitory->find($id);

            $em=$this->getDoctrine()->getManager();

            $em->remove($produit);
            $em->flush();

            return $this->redirectToRoute("home");

    }

}
