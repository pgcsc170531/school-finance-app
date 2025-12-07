<?php

namespace App\Tuition\Domain\Model;

use App\Repository\Tuition\Domain\Model\FeeItemRepository;
use App\Shared\Domain\ValueObject\Money;
use Doctrine\ORM\Mapping as ORM;

// We removed 'App\Entity' from the namespace above to match our folder structure
#[ORM\Entity(repositoryClass: FeeItemRepository::class)]
class FeeItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // --- FIX 1: Added the ORM Annotation so database saves this ---
    #[ORM\Embedded(class: Money::class)]
    private Money $amount;

    #[ORM\Column(enumType: FeeType::class)]
    private ?FeeType $type = null;

    #[ORM\ManyToOne(inversedBy: 'feeItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FeeStructure $structure = null;

    // --- FIX 2: Added the Constructor here ---
public function __construct(string $name, FeeType $type, Money $amount, FeeStructure $structure)
    {
        $this->name = $name;
        $this->type = $type;
        $this->amount = $amount;
        $this->structure = $structure; // <--- This fixes the NULL error
        // --- ADD THIS LINE ---
        // "Hey Structure, add me to your list!"
        $structure->addFeeItem($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    // --- FIX 3: Added the Getter for Amount ---
    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getType(): ?FeeType
    {
        return $this->type;
    }

    public function setType(FeeType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getStructure(): ?FeeStructure
    {
        return $this->structure;
    }

    public function setStructure(?FeeStructure $structure): static
    {
        $this->structure = $structure;
        return $this;
    }
}