<?php


class Bot extends Character
{
    function __construct(array $characterBDD) {
        parent::__construct($characterBDD);
    }

    public function tacle(Character $target){
        $attack = $this->level * rand(6, 13) + $this->strength;
        $before = $target->getLifePoints();
        $status = parent::getStatus($target->undergo($attack), $this, $target, $before, $attack);
        return $status;
    }
    public function attack(Character $target) {
        $status = $this->tacle($target);
        return $status;
    }
}