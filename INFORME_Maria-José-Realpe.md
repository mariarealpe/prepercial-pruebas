# Informe · Pruebas de la Unidad 2 y pipeline de CI/CD

## 1. Repositorio

https://github.com/mariarealpe/prepercial-pruebas

## 2. Pipeline en rojo y en verde (Paso 7.5)

**En rojo:** cambio de `>=` por `>` en `debeGenerarAlerta`.
Ejecución: https://github.com/mariarealpe/prepercial-pruebas/actions/runs/42914240558

![Pipeline en rojo](capturas%20pipelines/pipeline-rojo.png)

**En verde:** después de `git revert HEAD`.
Ejecución: https://github.com/mariarealpe/prepercial-pruebas/actions/runs/42914431425

![Pipeline en verde](capturas%20pipelines/pipeline-verde.png)

## 3. Salida de cobertura

```
Code Coverage Report:
 Summary:
  Classes: 100.00% (1/1)
  Methods: 100.00% (6/6)
  Lines:   100.00% (37/37)

Pronosticos\CalculadoraPronosticos
  Methods: 100.00% ( 6/ 6)   Lines: 100.00% ( 37/ 37)

Cobertura de líneas: 100.00 % (mínimo exigido: 90 %)
```

## 4. Preguntas

**¿Qué vieron en el rojo del Paso 2.1 y por qué es importante verlo antes de escribir el código?**

Error: `Call to undefined method Pronosticos\CalculadoraPronosticos::suavizacionExponencial()`. La prueba falló porque el método todavía no existía. Ver el rojo demuestra que la prueba sí puede fallar. Si pasara desde el principio, no estaría comprobando nada. Además, la prueba define primero qué debe hacer el método y luego se escribe el código para cumplirlo.

**¿Por qué el MAPE del caso `[80, 0, 95]` se divide entre 2?**

El periodo con demanda real 0 no se puede expresar como porcentaje, porque habría que dividir entre cero. Por eso se excluye. Quedan dos periodos válidos: el primero, con error 0 % (80 contra 80), y el tercero, con |95 − 100| / 95 · 100 = 5.26 %. El promedio es (0 + 5.26) / 2 = 2.63 %.

**¿Qué diferencia hay entre una prueba unitaria y un escenario BDD en este proyecto?**

La prueba unitaria está escrita en PHP para quien programa. Verifica un método aislado con muchos casos, incluidos errores de validación y casos borde. El escenario BDD está escrito en español (Gherkin) desde el punto de vista del negocio ("Dado… Cuando… Entonces…"), y alguien de Producción puede leerlo y confirmar que la regla es la correcta. Behat lo ejecuta a través de `FeatureContext`, que traduce cada frase a una llamada al código.

**¿Por qué el proyecto necesita `bin/verificar-cobertura.php` para que el pipeline falle con cobertura baja?**

PHPUnit mide la cobertura y genera el reporte, pero no tiene una opción para fallar cuando el porcentaje es bajo: termina en éxito si las pruebas pasan. El script lee `coverage.xml`, calcula el porcentaje de líneas y termina con código de error si no llega al 90 %. Así el job falla y el despliegue no corre.

**En el Paso 7.5, ¿qué pruebas fallaron y por qué el despliegue no corrió?**

Fallaron las que verifican el caso límite "error igual al umbral": la prueba unitaria `testAlertaCuandoElErrorEsIgualAlUmbral` y el escenario BDD "El error es igual al umbral". Con `>`, un error de 10 % contra un umbral de 10 % ya no genera alerta. En GitHub fallaron los jobs de *Pruebas unitarias*, *Pruebas BDD* y *Cobertura* (este último también corre las pruebas). El análisis estático pasó, porque el código seguía siendo válido. El despliegue tiene `needs` con los cuatro jobs, así que, como no todos terminaron bien, quedó en *skipped*.
