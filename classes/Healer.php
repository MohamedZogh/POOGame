<?php


class Healer extends Character
{
    public $maxLife;
    function __construct(array $characterBDD) {
        parent::__construct($characterBDD);
        if ( $this->level <=9){
            $this->lifePoints = 100 + ($this->level - 1) * 35 + $this->addLife;
            $this->armor = ($this->level - 1) * 10 + $this->addArmor;
        }
        elseif ( $this->level <=13){
            $this->lifePoints = 100 + ($this->level - 1) * 85 + $this->addLife;
            $this->armor = ($this->level - 1) * 15 + $this->addArmor;
        }
        elseif ($this->level > 13){
            $this->lifePoints = 100 + ($this->level - 1) * 210 + $this->addLife;
            $this->armor = ($this->level - 1) * 25 + $this->addArmor;
        }
        $this->maxLife = $this->lifePoints;
    }

    public function MagicWand(Character $target){
        $attack = $this->level * rand(5, 15) + $this->strength;
        $before = $target->getLifePoints();
        $status = parent::getStatus($target->undergo($attack), $this, $target, $before, $attack);
        return $status;
    }

    public function heal(){
        $soinBoost = $this->maxLife/5 + $this->level * 10 * rand(4,9);
        $need = $this->maxLife - $this->lifePoints;
        $need >= $soinBoost ? $soin = $soinBoost : $soin = $need;
        $this->lifePoints += $soin;
        return "$this->name se soigne +" . $soin . " ;) ( soin = $soinBoost, maxLife = {$this->maxLife})";
    }

    public function poison(Character $target){
        $dmgPoison = $this->level * 5 + $this->strength / 5;
        $duration = 5 + $target->getPoisonDuration();
        if ($duration > 10){
            $dmgPoison *= ($duration / 5);
        }
        $target->setPoison($dmgPoison);
        $target->setPoisonDuration($duration);
        return;
    }
    public function attack(Character $target) {
        if ($this->maxLife == $this->lifePoints){
            $status = $this->MagicWand($target);
        }
        else{
            // 2 chance sur 12:
            $attackChoice = rand(0,11);
            $attackChoice >=6 ? $status = $this->heal() : $status = $this->MagicWand($target);
        }
        $this->poison($target);
        return $status;
    }
}