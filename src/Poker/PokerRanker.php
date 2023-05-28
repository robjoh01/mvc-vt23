<?php

namespace App\Poker;

use App\Card\CardHand;

class PokerRank
{
    public const HIGH_CARD = 'High Card';
    public const ONE_PAIR = 'One Pair';
    public const TWO_PAIR = 'Two Pair';
    public const THREE_OF_A_KIND = 'Three of a Kind';
    public const STRAIGHT = 'Straight';
    public const FLUSH = 'Flush';
    public const FULL_HOUSE = 'Full House';
    public const FOUR_OF_A_KIND = 'Four of a Kind';
    public const STRAIGHT_FLUSH = 'Straight Flush';
    public const ROYAL_FLUSH = 'Royal Flush';
}

class PokerRanker
{
    /**
     * Get the rank of the given hand
     */
    public static function getHandRank(CardHand $hand): string
    {
        if (self::isRoyalFlush($hand)) {
            return PokerRank::ROYAL_FLUSH;
        }

        if (self::isStraightFlush($hand)) {
            return PokerRank::STRAIGHT_FLUSH;
        }

        if (self::isFourOfAKind($hand)) {
            return PokerRank::FOUR_OF_A_KIND;
        }

        if (self::isFullHouse($hand)) {
            return PokerRank::FULL_HOUSE;
        }

        if (self::isFlush($hand)) {
            return PokerRank::FLUSH;
        }

        if (self::isStraight($hand)) {
            return PokerRank::STRAIGHT;
        }

        if (self::isThreeOfAKind($hand)) {
            return PokerRank::THREE_OF_A_KIND;
        }

        if (self::isTwoPair($hand)) {
            return PokerRank::TWO_PAIR;
        }

        if (self::isOnePair($hand)) {
            return PokerRank::ONE_PAIR;
        }

        return PokerRank::HIGH_CARD;
    }

    /**
     * Check if the given hand is a Royal Flush
     */
    private static function isRoyalFlush(CardHand $hand): bool
    {
        // Hand contains 10, J, Q, K, A of the same suit.

        $valueArray = $hand->getValueArray();
        $suitArray = $hand->getSuitArray();

        return in_array("10", $valueArray) &&
            in_array("J", $valueArray) &&
            in_array("Q", $valueArray) &&
            in_array("K", $valueArray) &&
            in_array("A", $valueArray) &&
            self::areAllElementsDuplicates($suitArray);
    }

    /**
     * Check if the given hand is a Straight Flush
     */
    private static function isStraightFlush(CardHand $hand): bool
    {
        // Hand contains five consecutive cards of the same suit.

        $scoreArray = $hand->getScoreArray();
        $suitArray = $hand->getSuitArray();

        return self::isConsecutive($scoreArray) && self::areAllElementsDuplicates($suitArray);
    }

    /**
     * Check if the given hand has four of a kind
     */
    private static function isFourOfAKind(CardHand $hand): bool
    {
        // Four cards of the same value.

        $array = $hand->getScoreArray();
        $scoreCounts = array_count_values($array);

        foreach ($scoreCounts as $count) {
            if ($count === 4) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given hand has a full house
     */
    private static function isFullHouse(CardHand $hand): bool
    {
        $scores = $hand->getScoreArray();
        $scoreCounts = array_count_values($scores);

        $hasThreeOfAKind = false;
        $hasPair = false;

        foreach ($scoreCounts as $score => $count) {
            if ($count === 3) {
                $hasThreeOfAKind = true;
            }
            if ($count === 2) {
                $hasPair = true;
            }
        }

        return $hasThreeOfAKind && $hasPair;
    }

    /**
     * Check if the given hand has a flush
     */
    private static function isFlush(CardHand $hand): bool
    {
        // Hand contains five cards of the same suit.

        $array = $hand->getSuitArray();

        if (count($array) < 5) {
            return false;
        }

        return self::areAllElementsDuplicates($array);
    }

    /**
     * Check if the given hand is a straight
     */
    private static function isStraight(CardHand $hand): bool
    {
        // Hand contains five consecutive cards regardless of the suit.

        $array = $hand->getScoreArray();
        return self::isConsecutive($array);
    }

    /**
     * Check if an array of five elements is consecutive
     * @param array<int> $array
     */
    private static function isConsecutive($array): bool
    {
        if (count($array) <= 4) {
            return false;
        }

        sort($array, SORT_NUMERIC);

        // Check if the difference between the maximum and minimum values is 4
        // and the array contains no duplicates
        return ($array[4] - $array[0] === 4) && (count(array_unique($array)) === 5);
    }

    /**
     * Check if the given hand has three of a kind
     */
    private static function isThreeOfAKind(CardHand $hand): bool
    {
        $array = $hand->getScoreArray();
        $scoreCounts = array_count_values($array);

        foreach ($scoreCounts as $count) {
            if ($count === 3) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given hand has two pairs
     */
    private static function isTwoPair(CardHand $hand): bool
    {
        $array = $hand->getScoreArray();
        $scoreCounts = array_count_values($array);

        $pairCount = 0;
        foreach ($scoreCounts as $count) {
            if ($count === 2) {
                $pairCount++;
            }
        }

        return $pairCount === 2;
    }

    /**
     * Check if the given hand has one pair
     */
    private static function isOnePair(CardHand $hand): bool
    {
        $array = $hand->getScoreArray();
        return count($array) > count(array_unique($array));
    }

    /**
     * @param array<int|string> $array
     */
    private static function areAllElementsDuplicates($array): bool
    {
        return (count(array_unique($array, SORT_REGULAR)) === 1);
    }
}
