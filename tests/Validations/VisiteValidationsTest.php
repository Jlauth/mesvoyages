<?php

namespace App\Tests\Validations;

use App\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Description of VisiteValidationsTest
 *
 * @author Jean
 */
class VisiteValidationsTest extends KernelTestCase {
    
    /**
     * Création d'un objet de type Visite
     * @return Visite
     */
    public function getVisite(): Visite {
        return (new Visite())
                ->setVille("New York")
                ->setPays("USA");
    }
    
    /**
     * 
     * @param Visite $visite
     * @param int $nbErreursAttendues
     */
    public function assertErrors(Visite $visite, int $nbErreursAttendues, string $message="") {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($visite);
        // logs si erreur dd($error);
        $this->assertCount($nbErreursAttendues, $error, $message);
    }
    
    /**
     * Test sur la validité d'une note
     */
    public function testValidNoteVisite() {
        $this->assertErrors($this->getVisite()->setNote(10), 0, "10 devrait réussir");
        $this->assertErrors($this->getVisite()->setNote(0), 0, "0 devrait réussir");
        $this->assertErrors($this->getVisite()->setNote(20), 20, "20 devrait réussir");
    }
    
    /**
     * Test sur la non validité d'une note
     */
    public function testNonValidNoteVisite() {
        $this->assertErrors($this->getVisite()->setNote(21), 1, "21 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(-1), 1, "-1 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(-5), 1, "-5 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(25), 1, "25 devrait échouer");
    }
    
    /**
     * Test sur la validité de la température maximale
     */
    public function testValidTempmaxVisite() {
        $this->assertErrors($this->getVisite()->setTempmin(18)->setTempmax(20), 0, "min=18, max=20 devrait réussir");
        $this->assertErrors($this->getVisite()->setTempmin(19)->setTempmax(20), 0, "min=19 max=20 devrait réussir");
    }
    
    /**
     * Test sur la non validité d'une température maximale
     */
    public function testNonValidTempMaxVisite() {
        $this->assertErrors($this->getVisite()->setTempmin(20)->setTempmax(18), 1, "min=20 max=18 devrait échouer");
        $this->assertErrors($this->getVisite()->setTempmin(20)->setTempmax(20), 1, "min=20 max=20 devrait échouer");                
    }
    
    /**
     * Test sur la validité de la date de création
     */
    public function testValidDatecreationVisite() {
        $aujourdhui = new \DateTime();
        $this->assertErrors($this->getVisite()->setDatecreation($aujourdhui), 1, "demain devrait échouer");
        $plustot = (new \DateTime())->sub(new \DateInterval("P5D"));
        $this->assertErrors($this->getVisite()->setDatecreation($plustot), 0, "plus tôt devrait réussir");
    }
    
    /**
     * Test sur la non validité de la date de création
     */
    public function testNonValidDatecreationVisite() {
        $demain = (new DateTime())->add(new \DateInterval("P1D"));
        $this->assertErrors($this->getVisite()->setDatecreation($demain), 1, "demain devrait échouer");
        $plustard = (new DateTime())->add(new DateInterval("P5D"));
        $this->assertErrors($this->getVisite()->setDatecreation($plustard), 1, "plus tard devrait échouer");
    }
}
