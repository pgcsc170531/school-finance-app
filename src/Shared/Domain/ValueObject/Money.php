<?php

namespace App\Shared\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Embeddable]
class Money
{
    #[ORM\Column(type: 'integer')]
    private int $amount; // Stored in Kobo (e.g., 1000 = N10.00)

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency; // NGN, USD

    public function __construct(int $amount, string $currency = 'NGN')
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Money amount cannot be negative");
        }
        
        $this->amount = $amount;
        $this->currency = $currency;
    }

    // Helper: Create Money from Naira (e.g. 5000.50 -> 500050)
    public static function fromNaira(float $amount): self
    {
        return new self((int) round($amount * 100), 'NGN');
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    // Display formatted (e.g., "NGN 5,000.00")
    public function __toString(): string
    {
        return sprintf('%s %s', $this->currency, number_format($this->amount / 100, 2));
    }
}