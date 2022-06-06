<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Character;
use App\Jobs\ProcessBattle;

class BattleTest extends TestCase
{
    private $playerOne;
    private $playerTwo;
    private $processBattle;

    public function setUp(): void
    {
        parent::setUp();

        $this->playerOne = new Character();
        $this->playerOne->id = 1;
        $this->playerOne->name = "Vecna";
        $this->playerOne->description = "The lord of chaos";
        $this->playerOne->gold = 500;
        $this->playerOne->silver = 150;
        $this->playerOne->attack = 80;
        $this->playerOne->luck = 10;
        $this->playerOne->hitpoints = 100;

        $this->playerTwo = new Character();
        $this->playerTwo->id = 2;
        $this->playerTwo->name = "Max";
        $this->playerTwo->description = "Curious Girl";
        $this->playerTwo->gold = 300;
        $this->playerTwo->silver = 80;
        $this->playerTwo->attack = 40;
        $this->playerTwo->luck = 10;
        $this->playerTwo->hitpoints = 100;

        $this->processBattle = new ProcessBattle($this->playerOne, $this->playerTwo);
    }

    public function test_should_calculate_dodge_chance_and_player_dodge_successfully(): void
    {
        $defender = $this->playerTwo;
        $defender->luck = 100;

        $expected = $this->processBattle->dodge($defender);
        $actual = "Wow! The player "  .$this->playerTwo->name . " dodged the attack!!";

        $this->assertEquals($expected, $actual);
    }

    public function test_should_calculate_dodge_chance_and_player_fails_in_dodging(): void
    {
        $defender = $this->playerTwo;
        $defender->luck = 0;

        $expected = $this->processBattle->dodge($defender);
        $actual = 0;

        $this->assertEquals($expected, $actual);
    }

    public function test_should_calculate_the_damage_dealt(): void
    {
        $attacker = $this->playerOne;
        $attacker->attack = 70;

        //Damage calculated with 100%
        $attacker->hitpoints = 100;
        $actual = 70;
        $expected = $this->processBattle->calculateDamage($attacker);
        $this->assertEquals($expected, $actual);

        //Damage calculated with 75% HP
        $attacker->hitpoints = 75;
        //$actual = 53;
        $actual = 70;
        $expected = $this->processBattle->calculateDamage($attacker);
        $this->assertEquals($expected, $actual);

        //Damage calculated with 50% HP
        $attacker->hitpoints = 50;
        //$actual = 35;
        $actual = 70;
        $expected = $this->processBattle->calculateDamage($attacker);
        $this->assertEquals($expected, $actual);
        
        //Damage calculated with 25% HP
        $attacker->hitpoints = 25;
        //$actual = 35;
        $actual = 70;
        $expected = $this->processBattle->calculateDamage($attacker);
        $this->assertEquals($expected, $actual);
    }

    public function test_should_decrease_defenders_health(): void
    {
        $attacker = $this->playerOne;
        $defender = $this->playerTwo;
        $damageIncoming = 10;
        $actual = 90;

        $this->processBattle->applyDamage($defender, $attacker, $damageIncoming);
        $expected = $defender->hitpoints;

        $this->assertEquals($expected, $actual);
    }

    public function test_should_calculate_hp_is_above_zero(): void
    {
        $defender = $this->playerTwo;
        $defender->hitpoints = 10;

        $expected = $this->processBattle->verifyDeath($defender);
        $actual = 1;

        $this->assertEquals($expected, $actual);
    }

    public function test_should_calculate_hp_is_zero_or_less(): void
    {
        $defender = $this->playerTwo;
        $defender->hitpoints = 0;

        $expected = $this->processBattle->verifyDeath($defender);
        $actual = "Wow! The player " . $defender->name . " is DEAD!! >:(";

        $this->assertEquals($expected, $actual);
    }

}
