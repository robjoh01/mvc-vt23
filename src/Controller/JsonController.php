<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JsonController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function apiHome(): Response
    {
        return $this->render('api/home.html.twig');
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(
        SessionInterface $session
    ): Response {
        // $dicehand = $session->get("pig_dicehand");

        // $data = [
        //     "rollUrl" => $this->generateUrl('pig_roll'),
        //     "saveUrl" => $this->generateUrl('pig_save'),
        //     "restartUrl" => $this->generateUrl('pig_init_get'),
        //     "pigDices" => $session->get("pig_dices"),
        //     "pigRound" => $session->get("pig_round"),
        //     "pigTotal" => $session->get("pig_total"),
        //     "diceValues" => $dicehand->getString()
        // ];

        return $this->render('api/deck.html.twig', $data);
    }

    #[Route("/api/deck/draw", name: "api_deck_shuffle", methods: ['POST'])]
    public function apiDeckDraw(
        SessionInterface $session
    ): Response {

        // som drar 1 eller :number kort från kortleken och visar upp dem i en JSON struktur samt antalet kort som är kvar i kortleken. Kortleken sparas i sessionen så om man anropar dem flera gånger så minskas antalet kort i kortleken.

        return $this->redirectToRoute('api_deck');
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {

        return $this->redirectToRoute('api_deck');
    }

    #[Route("/api/deck/shuffle/:number", name: "api_deck_shuffle", methods: ['POST'])]
    public function apiDeckShuffleWithNumber(
        SessionInterface $session
    ): Response {

        // som drar 1 eller :number kort från kortleken och visar upp dem i en JSON struktur samt antalet kort som är kvar i kortleken. Kortleken sparas i sessionen så om man anropar dem flera gånger så minskas antalet kort i kortleken.

        return $this->redirectToRoute('api_deck');
    }

    public function getDailyQuote()
    {
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
}
