<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'generate_product')]
    
    public function add_product(Request $request, ManagerRegistry $manager, ProduitRepository $produitRepository): Response
    {
        $em = $manager->getManager();

        $produit = new produit();
    
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
           
            $em->persist($produit);
            $em->flush();
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll(),
                'f' => $form->createView()]);

            }
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll(),
                'f' => $form->createView()]);
        }


        #[Route('/boutique.html', name: 'acheter_product')]
        public function pbooking(ProduitRepository $produitRepository ): Response
{      
    
    return $this->render('produit/acheter.html.twig',[
    'produits' => $produitRepository->findAll()]);


}
#[Route('/produit/edit/{id}', name: 'produit_edit')]
public function editProduct(Request $request, ManagerRegistry $manager, $id, ProduitRepository $produitRepository): Response
{       $em = $manager->getManager();

        $produit  = $produitRepository->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
       

        if ($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('generate_product');
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'f' => $form->createView()]);
        }         
        #[Route('/produit/delete/{id}', name: 'produit_delete')]
        public function deleteProduct(Request $request, ManagerRegistry $manager, $id, ProduitRepository $produitRepository): Response
        {
        $em = $manager->getManager();
        $produit1 = $produitRepository->find($id);

      
        $em->remove($produit1);
        $em->flush();
        
                return $this->redirectToRoute('generate_product');
     
    }
        }    



   






    



  

    
    
   