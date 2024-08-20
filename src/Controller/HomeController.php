<?php

namespace App\Controller;

use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function getResults(int $limit = 10): array
    {
        $fakeResults = [];
        $faker = FakerFactory::create('fr_FR');

        for ($i = 1; $i <= $limit; $i++) {
            // seed the random generator
            $faker->seed($i);
            $date = new Carbon($faker->dateTimeThisCentury());
            $fakeResults[] = [
                'seed' => $i,
                'id' => $faker->uuid(),
                'title' => $faker->sentence(15),
                'authors' => [
                    $faker->name(),
                    $faker->name(),
                ],
                'mentors' => [
                    $faker->name(),
                    $faker->name(),
                ],
                'keywords' => [
                    $faker->words(1, true),
                    $faker->words(1, true),
                    $faker->words(1, true),
                    $faker->words(1, true),
                    $faker->words(1, true),
                    $faker->words(1, true),
                ],
                'branch' => $faker->randomElement(['Mathématiques', 'Sciences', 'Informatique', 'Génie']),
                'institution' => $faker->company(),
                'year' => $date->year,
                'published' => $faker->boolean(),
                'published_at' => $date,
                'updated_at' => $date->addDays($faker->numberBetween(1, 100)),
                'summary' => $faker->sentence(150),
            ];
        }

        return $fakeResults;
    }

    public function getResult(int $seed): array
    {
        $faker = FakerFactory::create('fr_FR');
        $faker->seed($seed);

        $date = new Carbon($faker->dateTimeThisCentury());
        
        return [
            'id' => $faker->uuid(),
            'title' => $faker->sentence(15),
            'authors' => [
                $faker->name(),
                $faker->name(),
            ],
            'mentors' => [
                $faker->name(),
                $faker->name(),
            ],
            'keywords' => [
                $faker->words(1, true),
                $faker->words(1, true),
                $faker->words(1, true),
                $faker->words(1, true),
                $faker->words(1, true),
                $faker->words(1, true),
            ],
            'branch' => $faker->randomElement(['Mathématiques', 'Sciences', 'Informatique', 'Génie']),
            'institution' => $faker->company(),
            'year' => $date->year,
            'published' => $faker->boolean(),
            'published_at' => $date,
            'updated_at' => $date->addDays($faker->numberBetween(1, 100)),
            'summary' => $faker->sentence(150),
        ];
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request): Response
    {
        $q = $request->query->get('q', '');
        $isTitle = $request->query->get('is_title', null);
        $isAuthor = $request->query->get('is_author', null);
        $isPublished = $request->query->get('published', true);

        return $this->render('search.html.twig', [
            'q' => $q,
            'isTitle' => $isTitle,
            'isAuthor' => $isAuthor,
            'isPublished' => $isPublished,
            'results' => $this->getResults(),
        ]);
    }

    #[Route('/{id}', name: 'app_search_result')]
    public function searchResult(string $id, Request $request): Response
    {
        $seed = $request->query->get('seed', null);
        

        return $this->render('result.html.twig', [
            'item' => $this->getResult($seed),
        ]);
    }
}
