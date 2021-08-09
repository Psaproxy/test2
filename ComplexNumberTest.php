<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/ComplexNumber.php';

class ComplexNumberTest extends TestCase
{
    /**
     * @param int|float $realPart
     * @param int|float $imaginaryPart
     * @param string $postfix
     *
     * @dataProvider creatingDataProvider
     */
    public function testIsCanBeCreatedAndReturnValidValues(
        int|float $realPart,
        int|float $imaginaryPart,
        string    $postfix
    ): void
    {
        $number = new ComplexNumber($realPart, $imaginaryPart, $postfix);

        self::assertEquals($realPart, $number->realPart());
        self::assertEquals($imaginaryPart, $number->imaginaryPart());
        self::assertEquals($postfix, $number->postfix());
        self::assertEquals(
            $realPart . (0 > $imaginaryPart ? '-' : '+') . $realPart . $postfix,
            (string)$number
        );
    }

    public function creatingDataProvider(): \Iterator
    {
        yield [0, 0, 'i'];
        yield [1, 0, 'n'];
        yield [0, 1, 'i'];
        yield [0.1, 0.1, 'n'];
        yield [0.1, 0, 'i'];
        yield [0, 0.1, 'n'];
        yield [1.1, 1.1, 'i'];
        yield [1.1, 0, 'n'];
        yield [0, 1.1, 'i'];
        yield [-1, 0, 'n'];
        yield [0, -1, 'i'];
        yield [-0.1, -0.1, 'n'];
        yield [-0.1, 0, 'i'];
        yield [0, -0.1, 'n'];
        yield [-1.1, 1.1, 'i'];
        yield [1.1, -1.1, 'n'];
        yield [-1.1, -1.1, 'i'];
        yield [-1.1, 0, 'n'];
        yield [0, -1.1, 'i'];
    }

    /**
     * @param string $postfix
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @dataProvider invalidCreatingDataProvider
     */
    public function testIsThrowExceptionOnInvalidPostfix(
        string $postfix,
        string $exceptionClass,
        string $exceptionMessage
    ): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        new ComplexNumber(1, 2, $postfix);

        // заглушка
        self::assertEquals(true, true);
    }

    public function invalidCreatingDataProvider(): \Iterator
    {
        yield [
            '',
            \InvalidArgumentException::class,
            'Необходимо указать постфикс.',
        ];
        yield [
            ' ',
            \InvalidArgumentException::class,
            'Необходимо указать постфикс.',
        ];
    }

    public function testIsImmutable(): void
    {
        $number = new ComplexNumber(1, 1);
        $numberClone = clone $number;
        $subNumber = new ComplexNumber(1, 0);
        $number->add($subNumber);

        self::assertEquals($numberClone, $number);
    }

    /**
     * @param int|float $realPart
     * @param int|float $imaginaryPart
     * @param int|float $subRealPart
     * @param int|float $subImaginaryPart
     * @param int|float $expectedRealPart
     * @param int|float $expectedImaginaryPart
     *
     * @dataProvider addingValuesDataProvider
     */
    public function testIsCanAdd(
        int|float $realPart,
        int|float $imaginaryPart,
        int|float $subRealPart,
        int|float $subImaginaryPart,
        int|float $expectedRealPart,
        int|float $expectedImaginaryPart,
    ): void
    {
        $number = new ComplexNumber($realPart, $imaginaryPart);
        $subNumber = new ComplexNumber($subRealPart, $subImaginaryPart);
        $resultNumber = $number->add($subNumber);

        self::assertEquals($expectedRealPart, $resultNumber->realPart());
        self::assertEquals($expectedImaginaryPart, $resultNumber->imaginaryPart());
    }

    public function addingValuesDataProvider(): \Iterator
    {
        yield [0, 0, 0, 0, 0, 0];
        yield [1, 0, 0, 0, 1, 0];
        yield [1, 0, 1, 0, 2, 0];
        yield [1, 1, 1, 0, 2, 1];
        yield [1, 1, 1, 1, 2, 2];
        yield [0, 1, 0, 1, 0, 2];
        yield [0, 1, 1, 1, 1, 2];
        yield [1, 1, 0, 1, 1, 2];
        yield [1, 1, 1, 1, 2, 2];
        yield [1, 1, -1, 1, 0, 2];
        yield [1, 1, -1, -2, 0, -1];
    }

    /**
     * @param int|float $realPart
     * @param int|float $imaginaryPart
     * @param int|float $subRealPart
     * @param int|float $subImaginaryPart
     * @param int|float $expectedRealPart
     * @param int|float $expectedImaginaryPart
     *
     * @dataProvider subtractingValuesDataProvider
     */
    public function testIsCanSubtract(
        int|float $realPart,
        int|float $imaginaryPart,
        int|float $subRealPart,
        int|float $subImaginaryPart,
        int|float $expectedRealPart,
        int|float $expectedImaginaryPart,
    ): void
    {
        $number = new ComplexNumber($realPart, $imaginaryPart);
        $subNumber = new ComplexNumber($subRealPart, $subImaginaryPart);
        $resultNumber = $number->subtract($subNumber);

        self::assertEquals($expectedRealPart, $resultNumber->realPart());
        self::assertEquals($expectedImaginaryPart, $resultNumber->imaginaryPart());
    }

    public function subtractingValuesDataProvider(): \Iterator
    {
        yield [0, 0, 0, 0, 0, 0];
        yield [1, 0, 0, 0, 1, 0];
        yield [1, 0, 1, 0, 0, 0];
        yield [1, 1, 1, 0, 0, 1];
        yield [1, 1, 1, 1, 0, 0];
        yield [0, 1, 0, 1, 0, 0];
        yield [0, 1, 1, 1, -1, 0];
        yield [1, 1, 0, 1, 1, 0];
        yield [1, 1, 1, 1, 0, 0];
        yield [1, 1, -1, 1, 2, 0];
        yield [1, 1, -1, -2, 2, 3];
    }

    /**
     * @param int|float $realPart
     * @param int|float $imaginaryPart
     * @param int|float $subRealPart
     * @param int|float $subImaginaryPart
     * @param int|float $expectedRealPart
     * @param int|float $expectedImaginaryPart
     *
     * @dataProvider multiplyingValuesDataProvider
     */
    public function testIsCanMultiply(
        int|float $realPart,
        int|float $imaginaryPart,
        int|float $subRealPart,
        int|float $subImaginaryPart,
        int|float $expectedRealPart,
        int|float $expectedImaginaryPart,
    ): void
    {
        $number = new ComplexNumber($realPart, $imaginaryPart);
        $subNumber = new ComplexNumber($subRealPart, $subImaginaryPart);
        $resultNumber = $number->multiply($subNumber);

        self::assertEquals($expectedRealPart, $resultNumber->realPart());
        self::assertEquals($expectedImaginaryPart, $resultNumber->imaginaryPart());
    }

    public function multiplyingValuesDataProvider(): \Iterator
    {
        yield [0, 0, 0, 0, 0, 0];
        yield [1, 0, 0, 0, 0, 0];
        yield [1, 0, 1, 0, 1, 0];
        yield [1, 1, 1, 0, 1, 1];
        yield [1, 1, 1, 1, 0, 2];
        yield [0, 1, 0, 1, -1, 0];
        yield [0, 1, 1, 1, -1, 1];
        yield [1, 1, 0, 1, -1, 1];
        yield [1, 1, 1, 1, 0, 2];
        yield [1, 1, -1, 1, -2, 0];
        yield [1, 1, -1, -2, 1, -3];
    }

    /**
     * @param int|float $realPart
     * @param int|float $imaginaryPart
     * @param int|float $subRealPart
     * @param int|float $subImaginaryPart
     * @param int|float $expectedRealPart
     * @param int|float $expectedImaginaryPart
     *
     * @dataProvider dividingValuesDataProvider
     */
    public function testIsCanDivide(
        int|float $realPart,
        int|float $imaginaryPart,
        int|float $subRealPart,
        int|float $subImaginaryPart,
        int|float $expectedRealPart,
        int|float $expectedImaginaryPart,
    ): void
    {
        $number = new ComplexNumber($realPart, $imaginaryPart);
        $subNumber = new ComplexNumber($subRealPart, $subImaginaryPart);
        $resultNumber = $number->divide($subNumber);

        self::assertEquals($expectedRealPart, $resultNumber->realPart());
        self::assertEquals($expectedImaginaryPart, $resultNumber->imaginaryPart());
    }

    public function dividingValuesDataProvider(): \Iterator
    {
        yield [1, 0, 1, 0, 1, 0];
        yield [1, 1, 1, 0, 1, 1];
        yield [1, 1, 1, 1, 1, 0];
        yield [0, 1, 0, 1, 1, 0];
        yield [0, 1, 1, 1, 0.5, 0.5];
        yield [1, 1, 0, 1, 1, -1];
        yield [1, 1, 1, 1, 1, 0];
        yield [1, 1, -1, 1, 0, -1];
        yield [1, 1, -1, -2, -0.6, 0.2];
    }

    /**
     * @param int|float $realPart
     * @param int|float $imaginaryPart
     * @param int|float $subRealPart
     * @param int|float $subImaginaryPart
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @dataProvider dividingInvalidValuesDataProvider
     */
    public function testIsThrowExceptionOnInvalidDividing(
        int|float $realPart,
        int|float $imaginaryPart,
        int|float $subRealPart,
        int|float $subImaginaryPart,
        string    $exceptionClass,
        string    $exceptionMessage
    ): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        $number = new ComplexNumber($realPart, $imaginaryPart);
        $subNumber = new ComplexNumber($subRealPart, $subImaginaryPart);
        $number->divide($subNumber);

        // заглушка
        self::assertEquals(true, true);
    }

    public function dividingInvalidValuesDataProvider(): \Iterator
    {
        yield [
            0, 0,
            0, 0,
            \DivisionByZeroError::class,
            'Division by zero',
        ];
        yield [
            1, 0,
            0, 0,
            \DivisionByZeroError::class,
            'Division by zero',
        ];
    }
}
