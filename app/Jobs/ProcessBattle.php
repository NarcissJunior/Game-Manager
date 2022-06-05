<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBattle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attacker;
    protected $defender;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attacker, $defender)
    {
        $this->attacker = $attacker;
        $this->defender = $defender;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dodged = $this->dodge($this->defender);

        if ($dodged != 0){
            $damage = 0;
        }
        else
        {
            //$damage = calculateDamage($this->attacker);
        }
    }

    public function removeHp($value, $player)
    {
        $player->hitpoints = $player->hitpoints -  $value;
    }

    //TODO calculate damage
    public function calculateDamage($player)
    {
        $lostHitpoints = $actualHitpoints - $player->hitpoints;
        $minimumAtkPossible = $player->attack / 2;
        if ($powerOfDamage <= $minimumAtkPossible)
        {
            return $powerOfDamage;
        }
        return $powerOfDamage;
    }

    public function dodge($player)
    {
        $chance = range(1, 100);
        if ($chance <= $player->luck)
        {
            return "Wow! The player " . $player->name . "dodged the attack!!";
        }
        return 0;
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
