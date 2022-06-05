<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Character;
use App\Services\BattleService;

class ProcessBattle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Character $playerOne;
    protected Character $playerTwo;
    protected BattleService $characterService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($playerOne, $playerTwo)
    {
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->dodge($playerTwo) == 0)
        {
            $damageIncoming = $this->calculateDamage($playerOne);
            $this->applyDamage($playerOne);
        }
        else
        {
            $damageIncoming = 0;
        }

        $this->verifyDeath($playerTwo);
        
    }

    public function dodge($player)
    {
        $chance = rand(1, 100);
        if ($chance <= $player->luck)
        {
            return "Wow! The player " . $player->name . "dodged the attack!!";
        }
        return 0;
    }

    public function calculateDamage($player)
    {
        $capAttack = $player->attack / 2;
        $minimumAtkPossible = round($capAttack, 0, PHP_ROUND_HALF_UP);

        //$this->characterService->retrieveMaxHp($player);
        $player->attack = 70;
        $player->hitpoints = 100;

        $maxHp = 100;
        $currentHP = 70;

        $lostHpPercent = (0.1 * ($maxHp - $currentHP));

        if ($lostHp != 0)
        {

            return $powerOfDamage;
            $player->attack = $currentAtk;
        }
        return $powerOfDamage;
    }

    public function applyDamage($player)
    {
        return 10;
    }

    public function verifyDeath($player)
    {
        if($player->hitpoints <= 0)
            return "Wow! The player " . $player->name . "is DEAD!! >:(";
        else
            return 1;
    }

    public function removeHp($value, $player)
    {
        $player->hitpoints = $player->hitpoints -  $value;
    }

    public function decreaseResources($goldValue, $silverValue, $player)
    {
        $player->gold = $player->gold - $goldValue;
        $player->silver = $player->silver - $silverValue;

        return "Player " . $player->name . " lost " . $goldValue . " pieces of gold and " . $silverValue . " pieces of silver.";
    }

    public function increaseResources($goldValue, $silverValue, $player)
    {
        $player->gold = $player->gold + $goldValue;
        $player->silver = $player->silver + $silverValue;

        return "Player " . $player->name . " won " . $goldValue . " pieces of gold and " . $silverValue . " pieces of silver.";
    }

    public function calculateBattleResources($winner, $loser)
    {
        $percentage = (0.1 * rand(10, 20));
        $goldValue = round($loser->gold * $percentage);
        $silverValue = round($loser->silver * $percentage);

        $log[] = $this->decreaseResources($goldValue, $silverValue, $loser);
        $log[] = $this->increaseResources($goldValue, $silverValue, $winner);

        return "sei la";
    }
}
