<?php

namespace App\DataFixtures;

use App\Tuition\Domain\Model\FeeStructure;
use App\Tuition\Domain\Model\FeeItem;
use App\Tuition\Domain\Model\FeeType;
use App\Shared\Domain\ValueObject\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TuitionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        echo "Creating Fee Structure for Grade 1...\n";

        // 1. Create the Container (Grade 1 Fees)
        $grade1 = new FeeStructure();
        $grade1->setName("Grade 1 - First Term 2025");
        $manager->persist($grade1); // Tell Doctrine to "watch" this object

        // 2. Add Fee Items
        // Tuition: N50,000.00
        $tuition = new FeeItem(
            "Tuition Fee", 
            FeeType::TUITION, 
            Money::fromNaira(50000), 
            $grade1
        );
        $manager->persist($tuition);

        // Sports: N5,000.00
        $sports = new FeeItem(
            "Sports Levy", 
            FeeType::FACILITY, 
            Money::fromNaira(5000), 
            $grade1
        );
        $manager->persist($sports);

        // ICT: N10,000.00
        $ict = new FeeItem(
            "ICT / Computer", 
            FeeType::TECHNOLOGY, 
            Money::fromNaira(10000), 
            $grade1
        );
        $manager->persist($ict);

        // 3. Save everything to Database
        $manager->flush();
        
        // 4. VERIFICATION: Check if the math works
        // The total should be 50k + 5k + 10k = 65k
        echo "✅ Created: " . $grade1->getName() . "\n";
        echo "💰 Total Calculated: " . $grade1->calculateTotal() . "\n"; 
    }
}