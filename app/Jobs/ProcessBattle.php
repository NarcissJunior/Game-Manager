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
    protected $tempAtk;
    protected $tempDef;
    protected $log = [];

    public function __construct($attacker, $defender)
    {
        $this->attacker = $attacker;
        $this->defender = $defender;
    }

    public function handle()
    {
        $playerAlive = 1;
        $this->tempAtk = $this->attacker;
        $this->tempDef = $this->defender;

        $this->log["battlestart"] = "The battle is starting!!!";
        $this->log["startingplayers"] = "The player " . $this->attacker->name . " challenged " . $this->defender->name . " to a fight!!";

        while ($playerAlive === 1)
        {
            $this->log["attackerturn"] = "Now it is the turn to " . $this->tempAtk->name . " to attack!";
            $this->log["defenderturn"] = "Now it is the turn to " . $this->tempDef->name . " to defend!";

            if($this->dodge($this->tempDef) == 0)
            {
                $damageIncoming = $this->calculateDamage($this->tempAtk);
                $this->log['damageincoming'] = "Damage incoming!!";
                $this->applyDamage($this->tempDef, $this->tempAtk, $damageIncoming);
            }

            $playerAlive = $this->verifyDeath($this->tempDef);

            $this->switchsides($this->attacker, $this->tempAtk, $this->defender, $this->tempDef);
        }

        $loot = $this->calculateBattleResources($this->attacker, $this->defender);
        $this->updateRanking($this->attacker, $loot);

        $hashValue = "logfrombattle_" . rand(1, 999);
        Redis::set($hashValue, json_encode([
            $this->log
        ]));
    }

    public function switchsides($attacker, $tempAtk, $defender, $tempDef)
    {
        $this->log['startswitching'] = "Now we are switching attacker and defender";
        $tempTemp = $this->tempAtk;
        $this->tempAtk = $this->tempDef;
        $this->tempDef = $tempTemp;
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
        $this->log["currentHp"] = "The Player " . $playerDefender->name . " now have " . $playerDefender->hitpoints . " hitpoints.";
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
        {
            return 1;
        }
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
