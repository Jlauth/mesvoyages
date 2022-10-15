<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyagesControllerTest
 *
 * @author Jean
 */
class VoyagesControllerTest extends WebTestCase {
    
    /**
     * Test d'accès à une page web
     */
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * Test sur un contenu spécifique d'une page web
     */
    public function testContenuPage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/voyages');
        $this->assertSelectorTextContains('h1', 'Mes voyages');
        $this->assertSelectorTextContains('th', 'Ville');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Renault');
    }
    
    /**
     * Test d'une url vers une autre
     */
    public function testLinkVille() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        // clic sur un lien (le nom d'une ville)
        $client->clickLink('Grenade');
        // récupération du résultat du clic
        $response = $client->getResponse();
        // contrôle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et contrôle de validité
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/voyages/voyage/3', $uri);         
    }
    
    public function testFiltreVille() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        // simulation de la soumission du formulaire
        $crawler = $client->submitForm('Filtrer', [
            'recherche' => 'Grenade'
        ]);
        // vérification du nombre de lignes obtenues
        $this->assertCount(1, $crawler->filter('h5'));
        // vérifie si la ville correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Grenade');
    }
    
}

