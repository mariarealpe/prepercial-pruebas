<?php

declare(strict_types=1);

namespace Pronosticos\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Pronosticos\CalculadoraPronosticos;

final class SuavizacionExponencialTest extends TestCase
{
    private const SERIE = [120, 132, 128, 141, 138, 150];

    public function testPronosticaElSiguientePeriodoConAlfa03(): void
    {
        $resultado = (new CalculadoraPronosticos())->suavizacionExponencial(self::SERIE, 0.3);

        $this->assertEqualsWithDelta(137.5546, $resultado, 0.01);
    }
}
