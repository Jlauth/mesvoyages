<?php

namespace App\Tests\Repository;

use App\Entity\Visite;
use App\Repository\VisiteRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Description of VisiteRepositoryTest
 *
 * @author Jean
 */
class VisiteRepositoryTest extends KernelTestCase {
    
    /**
     * Recupère le repo de Visite
     * @return VisiteRepository
     */
    public function recupRepository(): VisiteRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(VisiteRepository::class);
        return $repository;
    }
    
     /**
     * Création d'une instance de Visite avec ville, pays & date de création
     * @return Visite
     */
    public function newVisite(): Visite {
        $visite = (new Visite())
                ->setVille("Strasbourg")
                ->setPays("Alsace")
                ->setDatecreation(new DateTime("now"));
        return $visite;
    }
    
    /**
     * Test sur le nombre de visite(s)
     */
    public function testNbVisites() {
        $repositry = $this->recupRepository();
        $nbVisites = $repositry->count([]);
        $this->assertEquals(2, $nbVisites);
    }
    
    /**
     * Test sur l'ajout de visite(s)
     */
    public function testAddVisite() {
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        $nbVisites = $repository->count([]);
        $repository->add($visite, true);
        $this->assertEquals($nbVisites + 1, $repository->count([]), "Erreur lors de l'ajout.");
    }
    
    /**
     * Test sur la suppression d'une visite
     */
    public function testRemoveVisite() {
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        $repository->add($visite, true);
        $nbVisites = $repository->count([]);
        $repository->remove($visite, true);
        $this->assertEquals($nbVisites - 1, $repository->count([]), "Erreur lors de la suppression.");
    }
    
    /**
     * Test sur une valeur si elle correspond bien à la recherche
     */
    public function testFindByEqualValue() {
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        $repository->add($visite, true);
        $visites = $repository->findByEqualValue("ville", "Strasbourg");
        $nbVisites = count($visites);
        $this->assertEquals(1, $nbVisites);
        $this->assertEquals("Strasbourg", $visites[0]->getVille());
    }  
}
