<?php

namespace App\Tuition\Domain\Model;

// Note: If this line shows an error, check if the Repository file exists in src/Repository
use App\Repository\Tuition\Domain\Model\FeeStructureRepository;
use App\Shared\Domain\ValueObject\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeeStructureRepository::class)]
class FeeStructure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, FeeItem>
     */
    #[ORM\OneToMany(targetEntity: FeeItem::class, mappedBy: 'structure', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $feeItems;

    public function __construct()
    {
        $this->feeItems = new ArrayCollection();
    }

    // --- DDD LOGIC: This method calculates the total cost of this structure ---
    public function calculateTotal(): Money
    {
        $totalCents = 0;
        foreach ($this->feeItems as $item) {
            $totalCents += $item->getAmount()->getAmount();
        }

        return new Money($totalCents);
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

    /**
     * @return Collection<int, FeeItem>
     */
    public function getFeeItems(): Collection
    {
        return $this->feeItems;
    }

    public function addFeeItem(FeeItem $feeItem): static
    {
        if (!$this->feeItems->contains($feeItem)) {
            $this->feeItems->add($feeItem);
            $feeItem->setStructure($this);
        }

        return $this;
    }

    public function removeFeeItem(FeeItem $feeItem): static
    {
        if ($this->feeItems->removeElement($feeItem)) {
            // set the owning side to null (unless already changed)
            if ($feeItem->getStructure() === $this) {
                $feeItem->setStructure(null);
            }
        }

        return $this;
    }
}