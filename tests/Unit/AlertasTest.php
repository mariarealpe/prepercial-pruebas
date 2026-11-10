<?php

declare(strict_types=1);

namespace Pronosticos\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pronosticos\CalculadoraPronosticos;

/** Pruebas unitarias de la regla de alertas de desviación. */
final class AlertasTest extends TestCase
{
    private CalculadoraPronosticos $calculadora;

    protected function setUp(): void
    {
        $this->calculadora = new CalculadoraPronosticos();
    }

    public function testAlertaCuandoElErrorSuperaElUmbral(): void
    {
        $this->assertTrue($this->calculadora->debeGenerarAlerta(12, 10));
    }

    public function testAlertaCuandoElErrorEsIgualAlUmbral(): void
    {
        // Caso límite acordado con Producción: igual al umbral sí alerta.
        $this->assertTrue($this->calculadora->debeGenerarAlerta(10, 10));
    }

    public function testNoAlertaCuandoElErrorNoAlcanzaElUmbral(): void
    {
        $this->assertFalse($this->calculadora->debeGenerarAlerta(7, 10));
    }

    public function testRechazaUmbralMayorQueCien(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El umbral debe estar entre 0 y 100');

        $this->calculadora->debeGenerarAlerta(12, 150);
    }

    public function testRechazaUmbralNegativo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El umbral debe estar entre 0 y 100');

        $this->calculadora->debeGenerarAlerta(12, -1);
    }
}
