<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct card graphic object without arguments and check default values.
     */
    public function testCreateCardGraphicCheckDefaults(): void
    {
        $graphic = new CardGraphic("♥", "A", 1);
        $this->assertInstanceOf("\App\Card\CardGraphic", $graphic);
    }

    /**
     * Construct card graphic without arguments and check default values.
     */
    public function testGetPropertiesForNull(): void
    {
        $graphic = new CardGraphic("", "", 1);

        $res = $graphic->getImagePath();
        $exp = "null";
        $this->assertEquals($exp, $res);

        $res = $graphic->getImageName();
        $exp = "null";
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct card graphic without arguments and check default values.
     */
    public function testGetPropertiesForHearts(): void
    {
        $graphic = new CardGraphic("♥", "A", 1);

        $res = $graphic->getImagePath();
        $exp = "img/cards/Hearts 1.png";
        $this->assertEquals($exp, $res);

        $res = $graphic->getImageName();
        $exp = "Hearts A";
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct card graphic without arguments and check default values.
     */
    public function testGetPropertiesForDiamonds(): void
    {
        $graphic = new CardGraphic("♦", "A", 1);

        $res = $graphic->getImagePath();
        $exp = "img/cards/Diamond 1.png";
        $this->assertEquals($exp, $res);

        $res = $graphic->getImageName();
        $exp = "Diamond A";
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct card graphic without arguments and check default values.
     */
    public function testGetPropertiesForClubs(): void
    {
        $graphic = new CardGraphic("♣", "A", 1);

        $res = $graphic->getImagePath();
        $exp = "img/cards/Clubs 1.png";
        $this->assertEquals($exp, $res);

        $res = $graphic->getImageName();
        $exp = "Clubs A";
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct card graphic without arguments and check default values.
     */
    public function testGetPropertiesForSpades(): void
    {
        $graphic = new CardGraphic("♠", "A", 1);

        $res = $graphic->getImagePath();
        $exp = "img/cards/Spades 1.png";
        $this->assertEquals($exp, $res);

        $res = $graphic->getImageName();
        $exp = "Spades A";
        $this->assertEquals($exp, $res);
    }
}