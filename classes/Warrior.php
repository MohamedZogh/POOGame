<?php

class Warrior extends Character
{
    function __construct(array $characterBDD) {
        parent::__construct($characterBDD);
        if ( $this->level <=9){
            $this->armor = 5 + ($this->level - 1) * 15 + $this->addArmor;
            $this->lifePoints = 100 + ($this->level - 1) * 20 + $this->addLife;
        }
        elseif ( $this->level <=13){
            $this->armor = 5 + ($this->level - 1) * 25 + $this->addArmor;
            $this->lifePoints = 100 + ($this->level - 1) * 40 + $this->addLife;
        }
        elseif ($this->level > 13){
            $this->armor = 5 + ($this->level - 1) * 45 + $this->addArmor;
            $this->lifePoints = 100 + ($this->level - 1) * 65 + $this->addLife;
        }
    }

    public function punch(Character $target, $boost = 1){
        $attack = $this->level * rand(5, 10) + $this->strength * $boost;
        $before = $target->getLifePoints();
        $status = parent::getStatus($target->undergo($attack), $this, $target, $before, $attack);
        return $status;
    }
    public function charge(){
        $this->charge = 1;
        return "$this->name prends un shaker.. ;)";
    }
    public function attack(Character $target) {
        if ($this->charge){
            $boost = rand(17, 40) / 10;
            $status = $this->punch($target, $boost);
            $this->charge = 0;
        }
        else{
            // 1 chance sur 10:
            $attackChoice = rand(0,9);
            $attackChoice >=8 ? $status = $this->charge() : $status = $this->punch($target);
        }
        return $status;
    }

}