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
    
    
    public function testValidNoteVisite() {
        $visite = $this->getVisite()->setNote(25);
        $this->assertErrors($visite, 1);
    }
    
    public function testNonValidTempMax() {
        $visite = $this->getVisite()
            ->setTempmin(20)
            ->setTempmax(18);
        $this->assertErrors($visite, 1, "ceci est un message d'erreur");
                    
    }
}
