<?php

declare(strict_types=1);

/**
 * VO (объект-значение) комплексного числа.
 */
class ComplexNumber
{
    private int|float $realPart;
    private int|float $imaginaryPart;
    private string $postfix;

    public function __construct(int|float $realPart, int|float $imaginaryPart, string $postfix = 'i')
    {
        $this->realPart = $realPart;
        $this->imaginaryPart = $imaginaryPart;

        if ('' === trim($postfix)) {
            throw new \InvalidArgumentException("Необходимо указать постфикс.");
        }
        $this->postfix = $postfix;
    }

    public function realPart(): int|float
    {
        return $this->realPart;
    }

    public function imaginaryPart(): int|float
    {
        return $this->imaginaryPart;
    }

    public function postfix(): string
    {
        return $this->postfix;
    }

    public function __toString()
    {
        return $this->realPart . (0 > $this->imaginaryPart ? '-' : '+') . $this->realPart . $this->postfix;
    }

    public function add(self $value): self
    {
        $realPart = $this->realPart + $value->realPart();
        $imaginaryPart = $this->imaginaryPart + $value->imaginaryPart();

        return new static($realPart, $imaginaryPart);
    }

    public function subtract(self $value): self
    {
        $realPart = $this->realPart - $value->realPart();
        $imaginaryPart = $this->imaginaryPart - $value->imaginaryPart();

        return new static($realPart, $imaginaryPart);
    }

    public function multiply(self $value): self
    {
        $realPart = $this->realPart * $value->realPart() - $this->imaginaryPart * $value->imaginaryPart();
        $imaginaryPart = $this->realPart * $value->imaginaryPart() + $this->imaginaryPart * $value->realPart();

        return new static($realPart, $imaginaryPart);
    }

    public function divide(self $value): self
    {
        $realPart =
            ($this->realPart * $value->realPart() + $this->imaginaryPart * $value->imaginaryPart())
            /
            ($value->realPart() * $value->realPart() + $value->imaginaryPart() * $value->imaginaryPart());

        $imaginaryPart =
            ($this->imaginaryPart * $value->realPart() - $this->realPart * $value->imaginaryPart())
            /
            ($value->realPart() * $value->realPart() + $value->imaginaryPart() * $value->imaginaryPart());

        return new static($realPart, $imaginaryPart);
    }
}
