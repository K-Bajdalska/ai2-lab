<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country}', name: 'app_weather_country', priority: 1)]
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(
        #[MapEntity(mapping: ['city' => 'city', 'country' => 'country'], stripNull: true)]
        Location $location,
        MeasurementRepository $measurementRepository
    ): Response {
        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
