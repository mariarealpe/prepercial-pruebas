<?php

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Pronosticos\CalculadoraPronosticos;

final class FeatureContext implements Context
{
    private CalculadoraPronosticos $calculadora;
    private float $umbral = 0;
    private bool $alerta = false;

    // Behat crea un FeatureContext nuevo para cada escenario.
    public function __construct()
    {
        $this->calculadora = new CalculadoraPronosticos();
    }

    /**
     * El texto después de @Given es el patrón del paso. :umbral captura el número
     * de la frase y lo entrega como parámetro: así el paso sirve para cualquier valor.
     *
     * @Given un umbral de alerta de :umbral %
     */
    public function unUmbralDeAlertaDe(float $umbral): void
    {
        $this->umbral = $umbral;
    }

    /**
     * @When el error del pronóstico es :error %
     */
    public function elErrorDelPronosticoEs(float $error): void
    {
        $this->alerta = $this->calculadora->debeGenerarAlerta($error, $this->umbral);
    }

    /**
     * @Then se genera una alerta
     */
    public function seGeneraUnaAlerta(): void
    {
        Assert::assertTrue($this->alerta);
    }

    /**
     * @Then no se genera una alerta
     */
    public function noSeGeneraUnaAlerta(): void
    {
        Assert::assertFalse($this->alerta);
    }
}
