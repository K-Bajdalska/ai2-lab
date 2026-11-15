<?php
namespace App\Tests\Entity;
use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);

        $this->assertEquals(
            (float) $expectedFahrenheit,
            $measurement->getFahrenheit()
        );
    }
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-0.5', '31.1'],
            ['10.5', '50.9'],
            ['-10', '14'],
            ['25.5', '77.9'],
            ['37', '98.6'],
            ['20.25', '68.45']
        ];
    }
}
