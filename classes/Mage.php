<?php

class Mage extends Character
{
    function __construct(array $characterBDD) {
        parent::__construct($characterBDD);
        if ( $this->level <=9){
            $this->strength += $this->addStrength + ($this->level - 1) * 10 ;
            $this->magicPoints = $this->magicPoints * 2 + ($this->level - 1) * 10 + $this->addStrength;
        }
        elseif ( $this->level <=13){
            $this->strength += $this->addStrength + ($this->level - 1) * 20 ;
            $this->magicPoints = $this->magicPoints * 2 + ($this->level - 1) * 20 + $this->addStrength;
        }
        elseif ($this->level > 13){
            $this->strength += $this->addStrength + ($this->level - 1) * 30 ;
            $this->magicPoints = $this->magicPoints * 2 + ($this->level - 1) * 30 + $this->addStrength;
        }
    }

    public function undergo($dmg) {
        // Bouclier non activer:
        if (!$this->shield){
            $dmgSudden= $dmg - $this->armor;
            // Eviter de soigner ( si armor > dmg => -- )
            if ($dmgSudden < 0){
                $dmgSudden = 0;
            }
        }
        else{
            $dmgSudden= ($dmg - $this->armor) / 10;
            $this->setShield(0);
        }
        //Gestion du poison:
        $duration = $this->getPoisonDuration();
        $duration ? [$dmgPoison = $this->poison, $duration -= 2, $this->poisonDuration = $duration] : $dmgPoison = 0;
        if ($duration < 0){
            $this->poisonDuration = 0;
        }
        $this->lifePoints -= $dmgSudden +$dmgPoison;
        //Life minimum 0 :
        if ($this->lifePoints < 0) {
            $this->lifePoints = 0;
        }
        return [$dmgSudden, $dmgPoison, $duration];
    }

    public function fireball(Character $target) {
        $useMagic = $this->level * rand(2, 4) ;
        $reste = $this->getMagicPoints() - $useMagic;
        if ( $reste >=0 ){
            $this->setMagicPoints($reste);
            $attack = $useMagic*2 +$this->strength;
        }
        elseif ( $this->getMagicPoints() > 0){
            $attack = $this->getMagicPoints() * 2 + $this->strength;
            $this->setMagicPoints(0);
        }
        else{
            $attack = 2 + $this->strength;
        }
        $before = $target->getLifePoints();
        $status = parent::getStatus($target->undergo($attack), $this, $target, $before, $attack);
        return $status;
    }
    public function shield(){
        $this->setShield(1);
        $status = "$this->name met en place son bouclier ;) !";
        return $status;
    }
    public function attack(Character $target){
        $attakChoice = rand(0,9);
        if ($attakChoice <=8 || $this->shield){
            $status = $this->fireball($target);
        }
        else{
            $status = $this->shield();
        }
        return $status;
    }
}