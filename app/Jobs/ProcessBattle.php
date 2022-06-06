<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Redis;

class ProcessBattle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attacker;
    protected $defender;
    protected $log = [];

    public function __construct($attacker, $defender)
    {
        $this->attacker = $attacker;
        $this->defender = $defender;
    }

    public function handle()
    {
        if($this->dodge($this->defender) == 0)
        {
            $damageIncoming = $this->calculateDamage($this->attacker);
            $this->applyDamage($this->defender, $this->attacker, $damageIncoming);
        }

        $playerAlive = $this->verifyDeath($this->defender);

        if($playerAlive != 1)
        {
            $loot = $this->calculateBattleResources($this->attacker, $this->defender);
            $this->updateRanking($this->attacker, $loot);
        }

        return $this->log;
    }

    public function dodge($player)
    {
        $chance = rand(1, 100);
        if ($chance <= $player->luck)
        {
            $dodgeLog = "Wow! The player " . $player->name . " dodged the attack!!";
            $this->log["dodge"] = $dodgeLog;
            return $dodgeLog;
        }
        return 0;
    }

    public function calculateDamage($player)
    {
        //$capAttack = $player->attack / 2;
        //$minimumAtkPossible = round($capAttack, 0, PHP_ROUND_HALF_UP);

        //$this->characterService->retrieveMaxHp($player);
        return $player->attack;
    }

    public function applyDamage($playerDefender, $playerAttacker, $damageIncoming)
    {
        $playerDefender->hitpoints = $playerDefender->hitpoints - $damageIncoming;
        $this->log["damagedealt"] = "The Player " . $playerDefender->name . " lost " . $damageIncoming . " hitpoints due to an attack from " . $playerAttacker->name . ".";
    }

    public function verifyDeath($player)
    {
        if($player->hitpoints <= 0)
        {
            $deathLog = "Wow! The player " . $player->name . " is DEAD!! >:(";
            $this->log["deathlog"] = $deathLog;
            return $deathLog;
        }
        else
            return 1;
    }

    public function decreaseResources($goldValue, $silverValue, $player)
    {
        $player->gold = $player->gold - $goldValue;
        $player->silver = $player->silver - $silverValue;

        $decreaseLog = "Player " . $player->name . " lost " . $goldValue . " pieces of gold and " . $silverValue . " pieces of silver.";
        $this->log["decreaselog"] = $decreaseLog;
        return $decreaseLog;
    }

    public function increaseResources($goldValue, $silverValue, $player)
    {
        $player->gold = $player->gold + $goldValue;
        $player->goldLoot = $player->goldLoot;
        $player->silver = $player->silver + $silverValue;
        $player->silverLoot = $player->silverLoot;

        $increaseLog = "Player " . $player->name . " received " . $goldValue . " pieces of gold and " . $silverValue . " pieces of silver.";
        $this->log["increaselog"] = $increaseLog;
        return $increaseLog;
    }

    public function calculateBattleResources($winner, $loser)
    {
        $values = [];
        $percentage = (0.1 * rand(10, 20));
        $goldValue = round($loser->gold * $percentage);
        $silverValue = round($loser->silver * $percentage);

        $this->log["loserresources"] = $this->decreaseResources($goldValue, $silverValue, $loser);
        $this->log["winnerresources"] = $this->increaseResources($goldValue, $silverValue, $winner);

        $values['gold'] = $goldValue;
        $values['silver'] = $silverValue;

        return $values;
    }

    public function updateRanking($player, $loot)
    {
        $index = 'leaders:loot';
        $goldPieces = $loot['gold'];
        Redis::zincrby($index, $goldPieces, $player->id);

        return "Player " . $player->name . " received " . $goldPieces . " gold pieces into your leaderboard!! =D";
    }
}
