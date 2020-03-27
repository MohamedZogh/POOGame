<?php

class Character
{
    // (Classe de personnage)

    protected $id;
    public $name;
    protected $lifePoints = 100;
    protected $magicPoints = 50;
    protected $shield = 0;
    protected $type;
    protected $charge = 0;
    protected $level = 1;
    protected $experience = 0;
    protected $victory = 1;
    protected $defeat = 0;
    protected $strength = 1;
    protected $armor = 0;
    protected $addStrength;
    protected $addArmor;
    protected $addLife;
    protected $points;
    protected $poison;
    protected $poisonDuration = 0;
    protected $necessaryExperience;

    function __construct(array $characterBDD) {
        $this->hydrate($characterBDD);
        if ( $this->level <=9){
            $this->strength += $this->level * 2;
            $this->lifePoints = 100 + ($this->level - 1) * 15 + $this->addLife;
            $this->armor = ($this->level - 1) * 2 + $this->addArmor;
        }
        elseif ($this->level <=13){
            $this->strength += $this->level * 9 + $this->addStrength;
            $this->lifePoints = 100 + ($this->level - 1) * 30 + $this->addLife;
            $this->armor = ($this->level - 1) * 4 + $this->addArmor;
        }
        elseif ($this->level > 13){
            $this->strength += $this->level * 19 + $this->addStrength;
            $this->lifePoints = 100 + ($this->level - 1) * 50 + $this->addLife;
            $this->armor = ($this->level - 1) * 12 + $this->addArmor;
        }
        $this->necessaryExperience = (100 + 50 * ($this->level - 1));
    }

    public function hydrate(array $characterBDD)
    {
        foreach ($characterBDD as $key => $value)
        {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }

    public function getName(){
        return $this->name;
    }

    public function setName(string $name){
        $this->name = $name;
    }

    public function getId(){
        return $this->id;
    }

    public function setId(string $id){
        $this->id = $id;
    }

    public function getMagicPoints(){
        return $this->magicPoints;
    }

    public function setMagicPoints($magicPoints){
        $this->magicPoints = $magicPoints;
    }

    public function getType(){
        return $this->type;
    }

    public function setType(string $type){
        $this->type = $type;
    }

    public function getLifePoints() {
        return $this->lifePoints;
    }

    public function setLifePoints(string $lifePoints){
        $this->lifePoints = $lifePoints;
    }

    public function getLevel(){
        return $this->level;
    }

    public function setLevel(string $level){
        $this->level = $level;
    }

    public function getExperience(){
        return $this->experience;
    }

    public function setExperience(string $experience){
        $this->experience = $experience;
    }

    public function getShield(){
        return $this->shield;
    }

    public function setShield($shield){
        $this->shield = $shield;
    }

    public function getVictory(){
        return $this->victory;
    }

    public function setVictory($victory){
        $this->victory = $victory;
    }

    public function getDefeat(){
        return $this->defeat;
    }

    public function setDefeat($defeat){
        $this->defeat = $defeat;
    }

    public function getStrength(){
        return $this->strength;
    }

    public function setStrength($strength){
        $this->strength = $strength;
    }

    public function getArmor(){
        return $this->armor;
    }

    public function setArmor($armor){
        $this->armor = $armor;
    }

    public function getAddStrength(){
        return $this->addStrength;
    }

    public function setAddStrength($addStrength){
        $this->addStrength = $addStrength;
    }

    public function getAddArmor(){
        return $this->addArmor;
    }

    public function setAddArmor($addArmor){
        $this->addArmor = $addArmor;
    }

    public function getAddLife(){
        return $this->addLife;
    }

    public function setAddLife($addLife){
        $this->addLife = $addLife;
    }

    public function getPoints(){
        return $this->points;
    }

    public function setPoints($points){
        $this->points = $points;
    }

    public function getPoison(){
        return $this->poison;
    }

    public function setPoison($poison){
        $this->poison = $poison;
    }

    public function getPoisonDuration(){
        return $this->poisonDuration;
    }

    public function setPoisonDuration($poisonDuration){
        $this->poisonDuration = $poisonDuration;
    }

    public function getNecessaryExperience(){
        return $this->necessaryExperience;
    }

    public function setNecessaryExperience($necessaryExperience){
        $this->necessaryExperience = $necessaryExperience;
    }

    public function undergo($dmg) {
        $dmgSudden= $dmg - $this->armor;
        if ($dmgSudden < 0){
            $dmgSudden = 0;
        }
        //Gestion du poison:
        $duration = $this->getPoisonDuration();
        $duration ? [$dmgPoison = $this->poison, $duration -= 1, $this->poisonDuration = $duration] : $dmgPoison = 0;
        $this->lifePoints -= $dmgSudden +$dmgPoison;
        //Life minimum 0 :
        if ($this->lifePoints < 0) {
            $this->lifePoints = 0;
        }
        return [$dmgSudden, $dmgPoison, $duration];
    }

    public function attack(Character $target) {
        $attack = rand(5, 15);
        $before = $target->getLifePoints();
        $status = parent::getStatus($target->undergo($attack), $this, $target, $before, $attack);
        return $status;
    }

    public static function getStatus($dmg, Character $attacker, Character $target, $before, $attack){
        $dmgSudden = $dmg[0];
        $dmgPoison = $dmg[1];
        $dmgTotal= $dmgSudden+$dmgPoison;
        $duration = $dmg[2];
        $after = $target->getLifePoints();
        if ($before == $after){
            $duration > 0 ? $status = "$attacker->name atk {$target->name} (-$dmgTotal)! {$target->name} s'est protégé ! (atk=$attack/ poison = $dmgPoison ($duration tours)" :
                            $status = "$attacker->name atk {$target->name} (-$dmgTotal)! {$target->name} s'est protégé ! (atk=$attack)";
        }
        else{
            $duration > 0 ? $status = "$attacker->name atk {$target->name} (-$dmgTotal)! Il reste {$target->getLifePoints()} à {$target->name} ! (atk=$attack / poison = $dmgPoison ($duration tours)" :
                            $status = "$attacker->name atk {$target->name} (-$dmgTotal)! Il reste {$target->getLifePoints()} à {$target->name} ! (atk=$attack)";
        }
        return $status;
    }

}