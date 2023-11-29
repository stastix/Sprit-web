<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    private const SESSION_KEY_PREFIX = 'panier_';

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {  
        $userId = $this->getUserId(); // Utilisation de la méthode pour obtenir l'ID utilisateur
        $panier = $this->getCartFromSession($session, $userId);
         dump($panier);
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $product = $produitRepository->find($id);
            
            // Vérifier si le produit existe
            if ($product) {
                $dataPanier[] = [
                    'produit' => $product,
                    'quantite' => $quantite,
                ];
                $total += $product->getPrixUnitaire() * $quantite;
            }
        }

        return $this->render('cart/index.html.twig', compact('dataPanier', 'total'));
    }

    #[Route('/add/{id}', name: 'cart_add', methods: ['POST'])]
public function add(Produit $product, SessionInterface $session)
{
    $userId = $this->getUserId();
    $panier = $this->getCartFromSession($session, $userId);
    $id = $product->getId();

    if (!empty($panier[$id])) {
        $panier[$id]++;
    } else {
        $panier[$id] = 1;
    }

    $this->saveCartToSession($session, $panier, $userId);

    return $this->redirectToRoute('cart_index');
}


    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Produit $product, SessionInterface $session)
    {
        $userId = $this->getUserId();
        $panier = $this->getCartFromSession($session, $userId);
        $id = $product->getId();

        if (!empty($panier[$id])) {
            $panier[$id] = max(1, $panier[$id] - 1);
            if ($panier[$id] === 1) {
                unset($panier[$id]);
            }
        }

        $this->saveCartToSession($session, $panier, $userId);

        return $this->redirectToRoute('cart_index');
    }

    // ... Autres méthodes du contrôleur ...

    private function getUserId(): int
    {
        // Vous pouvez remplacer cette méthode par la logique nécessaire pour obtenir l'ID utilisateur
        // par exemple, en utilisant le système de sécurité de Symfony.
        return 4; // Valeur statique pour l'exemple
    }

    private function getCartFromSession(SessionInterface $session, int $userId): array
    {
        $panierKey = self::SESSION_KEY_PREFIX . $userId;
        return $session->get($panierKey, []);
    }

    private function saveCartToSession(SessionInterface $session, array $panier, int $userId): void
    {
        $panierKey = self::SESSION_KEY_PREFIX . $userId;
        $session->set($panierKey, $panier);
    }

  
}
