<?php

require './classes/Autoloader.php';
\Classes\Autoloader::register();

$fctn=$_POST['fct'];
switch($fctn){
    Case "getCharacter":
        getCharacter();
        break;
    Case "fight":
        fight();
        break;
    Case "addCharacter":
        addCharacter();
        break;
    Case "setPoints":
        setPoints();
        break;
    Default:
        echo json_encode(['Error','404']);
        break;
}

function setPoints(){
//    $db = new Database;
    $pointsQuantity = $_POST['quantity'];
    $caracteristique = $_POST['caracteristique'];
    $name = $_POST['name'];

    $connected = Database::getCharacter($name);
    $connectedClass = $connected['type'];
    $connected = new $connectedClass($connected);
    $result = Database::upgradeWithPoints($connected, $pointsQuantity, $caracteristique);
    if ($result){
        echo json_encode(['response'=>'success', 'message'=>'C\'est fait ! Retournes-y !', 'usedQty' => $pointsQuantity]);
    }
    else{
        echo json_encode(['response'=>'error', 'message'=>'Pas assez de points..']);
    }
}

function addCharacter(){
    $pseudo = $_POST['pseudo'];
    $type = $_POST['type'];
//    $db = new Database;
    $character = Database::addCharacter($pseudo, $type);
    $character ? $response =['response'=>'success', 'message'=>'Bienvenue dans le game !'] : $response =['response'=>'error', 'message'=>'Pseudo indisponible'];
    echo json_encode($response);
}

function getCharacter(){
    $pseudo = $_POST['pseudo'];
//    $db = new Database;
    $characterBDD = Database::getCharacter($pseudo);
    if ($characterBDD){
        $typeClass = $characterBDD['type'];
        $character = new $typeClass($characterBDD);
        $perso = [
            'id' => $character->getId(),
            'name' => $character->getName(),
            'level' => $character->getLevel(),
            'experience' => $character->getExperience(),
            'necessaryExperience' => $character->getNecessaryExperience(),
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
        echo json_encode(['response'=>'success', 'perso'=>$perso]);
    }
}

function fight(){
    try{
//        $db = new Database;
        $pseudo = $_POST['pseudo'];
        $adversaire = $_POST['adversaire'];
//    echo json_encode(['response'=>'success', 'message'=>"$pseudo / $adversaire"]);
        $connected = Database::getCharacter($pseudo);
        $adversaire = Database::getCharacter($adversaire);
        if ($connected && $adversaire){
            $connectedClass = $connected['type'];
            $connected = new $connectedClass($connected);
            $adversaireClass = $adversaire['type'];
            $adversaire = new $adversaireClass($adversaire);
            $status = [];
            $connected->getLevel() > $adversaire->getLevel() ? [$first = $connected, $second = $adversaire] : [$first =$adversaire, $second = $connected];
            while ($connected->getLifePoints() > 0 && $adversaire->getLifePoints() > 0) {
                $status[] = $first->attack($second);
                if ($second->getLifePoints() > 0) {
                    $status[] = $second->attack($first);
                }
            }
            $connected->getLifePoints() > 0 ? [$winner =$connected, $looser = $adversaire] : [$winner =$adversaire, $looser = $connected];
            // Gain d'expérience:
            $differenceLevel= $looser->getLevel() - $winner->getLevel();

            $gain = Database::upExperience($differenceLevel);
            Database::updateCharacter($winner, $gain);
            // Enregistrer les victoires / défaites :
            Database::setResultFight($winner, 'victory');
            Database::setResultFight($looser, 'defeat');
            // Renvoyer l'utilisateur réinitialiser:
            $connected = Database::getCharacter($pseudo);
            $connected = new $connectedClass($connected);
            $adversaire = Database::getCharacter($_POST['adversaire']);
            $adversaire = new $connectedClass($adversaire);
            echo json_encode([
                'response'=>'success',
                'message'=>"<span style='color: red;'>$winner->name a mis une raclé à $looser->name</span>",
                'winner' => "$winner->name",
                'looser' => "$looser->name",
                'level' => $connected->getLevel(),
                'life' => $connected->getLifePoints(),
                'adversaireLevel' =>$adversaire->getLevel(),
                'adversaireLife' => $adversaire->getLifePoints(),
                'status' => $status,
                'experience' => $connected->getExperience(),
                'necessaryExperience' => $connected->getNecessaryExperience()
            ]);
        }
        else{
            echo json_encode(['response'=>'error', 'message'=> 'L\'un des combattants n\'existe pas']);
        }
    } catch(DBALException $e){
        $errorMessage = $e->getMessage();
        echo json_encode(['response'=>'error', 'message'=> $errorMessage]);
    }
    catch(\Exception $e){
        $errorMessage = $e->getMessage();
        echo json_encode(['response'=>'error', 'message'=> $errorMessage]);
    }
}