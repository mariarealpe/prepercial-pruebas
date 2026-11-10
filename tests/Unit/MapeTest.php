<?php

declare(strict_types=1);

namespace Pronosticos\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pronosticos\CalculadoraPronosticos;

/** Pruebas unitarias del error porcentual absoluto medio (MAPE). */
final class MapeTest extends TestCase
{
    private CalculadoraPronosticos $calculadora;

    protected function setUp(): void
    {
        $this->calculadora = new CalculadoraPronosticos();
    }

    public function testCalculaElErrorPorcentualAbsolutoMedio(): void
    {
        $resultado = $this->calculadora->mape([141, 138, 150], [126.67, 133.67, 135.67]);

        // (10.16 % + 3.14 % + 9.55 %) / 3 = 7.62 %
        $this->assertEqualsWithDelta(7.62, $resultado, 0.01);
    }

    public function testExcluyeLosPeriodosConDemandaCero(): void
    {
        $resultado = $this->calculadora->mape([80, 0, 95], [80, 0, 100]);

        // El periodo con demanda 0 no cuenta: (0 % + 5.26 %) / 2 = 2.63 %
        $this->assertEqualsWithDelta(2.63, $resultado, 0.01);
    }

    public function testRechazaSeriesSinPeriodosValidos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El MAPE no está definido');

        $this->calculadora->mape([0, 0], [10, 20]);
    }

    public function testRechazaSeriesDeDistintaLongitud(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('mismo número de periodos');

        $this->calculadora->mape([100, 110], [100]);
    }
}
