<?php


class Database
{
    public static $db_setting;
    private static $pdo;

    public static function getSettings(){
        if (self::$db_setting == null){
            $settings = require './config/config.php';
            self::$db_setting = $settings;
        }
        return self::$db_setting;
    }

    public static function getPDO(){
        self::getSettings();
        if (self::$pdo === null){
            $db_setting = self::$db_setting;
            $pdo = new PDO("mysql:host=".$db_setting['db_host'].';dbname='.$db_setting['db_name'], $db_setting['db_user'], $db_setting['db_pass']);
            self::$pdo = $pdo;
        }
        return self::$pdo;
    }

    public static function query($statement){
        $request = self::getPDO()->query($statement);
        $datas = $request->fetchAll(PDO::FETCH_ASSOC);
        return $datas;
    }

    public static function getAllCharacters(){
        $request = self::getPDO()->query("SELECT * FROM personnages ORDER  BY level");
        $characters = $request->fetchAll(PDO::FETCH_ASSOC);
        foreach ($characters as $character){
            $typeClass = $character['type'];
            $character = new $typeClass($character);
            $all[] = [
                'id' => $character->getId(),
                'name' => $character->getName(),
                'level' => $character->getLevel(),
                'experience' => $character->getExperience(),
                'type' => $typeClass,
                'life' => $character->getLifePoints(),
                'armor' => $character->getArmor(),
                'strength' => $character->getStrength(),
                'addArmor' => $character->getAddArmor(),
                'addStrength' => $character->getAddStrength(),
                'addLife' => $character->getAddLife(),
                'victory' => $character->getVictory(),
                'defeat' => $character->getDefeat(),
                'points' => $character->getPoints()
            ];
        }
        return $all;
    }

    public static function getClassement(){
        $request = self::getPDO()->query("SELECT * FROM personnages Order By victory DESC");
        $characters = $request->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach ($characters as $character){
            $typeClass = $character['type'];
            $character = new $typeClass($character);
            $all[] = [
                'numero' => $i++,
                'name' => $character->getName(),
                'level' => $character->getLevel(),
                'experience' => $character->getExperience(),
                'type' => $typeClass,
                'life' => $character->getLifePoints(),
                'armor' => $character->getArmor(),
                'strength' => $character->getStrength(),
                'addArmor' => $character->getAddArmor(),
                'addStrength' => $character->getAddStrength(),
                'addLife' => $character->getAddLife(),
                'victory' => $character->getVictory(),
                'defeat' => $character->getDefeat()
            ];
        }
        return $all;
    }

    public static function getCharacter($name){
        $request = self::getPDO()->query("SELECT * FROM personnages WHERE name = '$name'");
        $character = $request->fetch();
        return $character;
    }
    public static function addCharacter($name, $type){
        $exist = self::getCharacter($name);
        if ($exist){
            return false;
        }
        else{
            $request = self::getPDO()->prepare('INSERT INTO personnages(name, type) VALUES (:name, :type)');
            $request->bindValue(':name', $name);
            $request->bindValue(':type', $type);
            $request->execute();
            return true;
        }
    }

    public static function deleteCharacter(Character $character)
    {
        self::getPDO()->exec('DELETE FROM personnages WHERE id = '.$character->getId());
    }

    public static function updateCharacter(Character $character, int $gain)
    {
        if ( $character->getType() != 'Bot'){
            $request = self::getPDO()->prepare('UPDATE personnages SET level = :level, experience = :experience, points = :points  WHERE id = :id');
            $points = $character->getPoints();
            $experience = $character->getExperience() + $gain;
            $experienceToUp = (100 + 50 * ($character->getLevel() - 1));
            $experience >= $experienceToUp ? [ $experience = $experience%$experienceToUp, $level = $character->getLevel() + 1, $points += 5] : $level = $character->getLevel();
            $request->bindValue(':level', $level, PDO::PARAM_INT);
            $request->bindValue(':experience', $experience, PDO::PARAM_INT);
            $request->bindValue(':points', $points, PDO::PARAM_INT);
            $request->bindValue(':id', $character->getId(), PDO::PARAM_INT);
            $request->execute();
        }
        //Ne pas augmenter le niveau des Bot:
        else{
            $level = $character->getLevel();
        }
        return $level;
    }

    public static function upgradeWithPoints(Character $character, $pointsQuantity, $caracteristique){
        $points = $character->getPoints() - $pointsQuantity;
        $type = $character->getType();
        $method = 'get'.ucfirst($caracteristique);
        if( $character->getPoints() >= $pointsQuantity){
            $add = self::getAddQuantity($type, $caracteristique, $pointsQuantity);
            $quantity = $character->$method() + $add;
            $request = self::getPDO()->prepare("UPDATE personnages SET $caracteristique = :caracteristique, points = :points  WHERE id = :id");
            $request->bindValue(':caracteristique', $quantity, PDO::PARAM_INT);
            $request->bindValue(':points', $points, PDO::PARAM_INT);
            $request->bindValue(':id', $character->getId(), PDO::PARAM_INT);
            $request->execute();
            return true;
        }
        return false;

    }

    public static function getAddQuantity($type, $caracteristique, $pointsQuantity){
        if ($type == 'Warrior'){
            if ($caracteristique == 'addStrength'){
                $add = 6 * $pointsQuantity;
            }
            elseif ($caracteristique == 'addArmor'){
                $add = 15 * $pointsQuantity;
            }
            elseif ($caracteristique == 'addLife'){
                $add = 30 * $pointsQuantity;
            }
        }
        elseif ($type == 'Mage'){
            if ($caracteristique == 'addStrength'){
                $add = 12 * $pointsQuantity;
            }
            elseif ($caracteristique == 'addArmor'){
                $add = 2 * $pointsQuantity;
            }
            elseif ($caracteristique == 'addLife'){
                $add = 15 * $pointsQuantity;
            }
        }
        elseif ($type == 'Healer'){
            if ($caracteristique == 'addStrength'){
                $add = 4 * $pointsQuantity;
            }
            elseif ($caracteristique == 'addArmor'){
                $add = 10 * $pointsQuantity;
            }
            elseif ($caracteristique == 'addLife'){
                $add = 50 * $pointsQuantity;
            }
        }
        return $add;
    }

    public static function setResultFight($character, $resultat){
     $id = $character->getId();
     $request = self::getPDO()->query("SELECT * FROM personnages WHERE id = '$id'");
     $character = $request->fetch();
     $resultat == 'victory' ? $value = $character['victory'] + 1 : $value = $character['defeat'] + 1;
     $request = self::getPDO()->prepare("UPDATE personnages SET $resultat = :resultat WHERE id = :id");
     $request->bindValue(':resultat', $value, PDO::PARAM_INT);
     $request->bindValue(':id', $character['id'], PDO::PARAM_INT);
     $request->execute();
    }

    public static function upExperience($differenceLevel){
        if ($differenceLevel < -5 ){
            $gain = 0;
        }
        else if ( -5 <= $differenceLevel && $differenceLevel < -1 ){
            $gain = 40 / abs($differenceLevel);
        }
        else if( -1 <= $differenceLevel && $differenceLevel <= 1){
            $gain = 40;
        }
        else{
            $gain = 30 * $differenceLevel;
        }
        return $gain;
    }
}