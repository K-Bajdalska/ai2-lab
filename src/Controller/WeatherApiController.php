<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Service\WeatherUtil;
use App\Entity\Measurement;
use Symfony\Component\HttpFoundation\Response;
final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);
        if ($twig) {
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ], new Response('', 200, [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                ]));
            }
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ], new Response('', 200, [
                'Content-Type' => 'application/json',
            ]));
        }
        if ($format === 'csv') {
            $rows = array_map(fn(Measurement $m) => sprintf(
                '%s,%s,%s,%s,%s',
                $city,
                $country,
                $m->getDate()->format('Y-m-d'),
                $m->getCelsius(),
                $m->getFahrenheit()
            ), $measurements);

            $csv = implode("\n", array_merge(
                ['city,country,date,celsius,fahrenheit'],
                $rows
            ));
            return new Response($csv, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}
