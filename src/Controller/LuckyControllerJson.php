<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    private $number;

    /**
     * @Route("/api/lucky/number")
     */
    public function number(): Response
    {
        $this->number = random_int(0, 100);

        $data = [
            'lucky-number' => $this->number
        ];

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    function getDailyQuote() {
        // array of quotes to select between
        $quotes = array(
            "The best way to predict the future is to invent it.",
            "In the end, we will remember not the words of our enemies, but the silence of our friends.",
            "The greatest glory in living lies not in never falling, but in rising every time we fall."
        );

        // generate a random index for the quote array
        $index = rand(0, count($quotes) - 1);

        // return the selected quote
        return $quotes[$index];
    }

// Skapa en route /api/quote där du ger ett JSON svar som innehåller dagens citat. Växla slumpmässigt mellan (minst) tre olika citat som du själv hittat på eller lånat av någon. JSON svaret skall även innehålla dagens datum och en tidsstämpel för när svaret genererades.

    /**
     * @Route("/api/quote")
     */
    public function quote(): Response
    {
        $quote = $this->getDailyQuote();

        $data = [
            'date' => date('Y-m-d'),
            'quote' => $quote,
            'timestamp' => date('H:i:s', time())
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * @Route("/api/lucky/number2")
     */
    public function number2(): Response
    {
        $this->number = random_int(0, 100);

        $data = [
            'message' => 'Welcome to the lucky number API',
            'lucky-number' => $this->number
        ];

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }



    /**
     * @Route("/api/lucky/number/{min}/{max}")
     */
    public function number3(int $min, int $max): Response
    {
        $this->number = random_int($min, $max);

        $data = [
            'message' => 'Welcome to the lucky number API',
            'min number' => $min,
            'max number' => $max,
            'lucky-number' => $this->number
        ];

        return new JsonResponse($data);
    }
}
