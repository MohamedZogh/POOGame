<?php

require './classes/Autoloader.php';
\Classes\Autoloader::register();

//$settings = Database::getPDO();
//
//print_r($settings);

//$db = new Database;
//$charactersBDD = $db->getAllCharacter();
$charactersBDD = Database::getAllCharacters();
$charactersClassement = Database::getClassement();
if(isset($_GET['pseudo'])){
    $pseudoGet = $_GET['pseudo'];
}
else{
    $pseudoGet = '';
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Corona-game</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css" rel="stylesheet">
</head>
<body>
    <img id="background" src="assets/images/background.jpg" alt="">
    <img id="level-up" src="assets/images/levelUp.gif" alt="">
    <h1>Prêt pour une baston ?</h1>
    <span>Entre ton pseudo !</span>
    <input type="text" class="form-control" id="pseudo" placeholder="Pikachu" style="width: 10vw" value="<?php echo $pseudoGet ?>">
    <button id="connexion" type="button" class="btn btn-info" >Connexion</button>
    <button id="inscription-btn" type="button" class="btn btn-success" >Inscription</button>
    <div id="modal-container">
        <div id="inscription" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pseudo" >Aller hop !</h5>
                <button type="button" class="close-inscription" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="inscription-name" placeholder="Pseudo">
                <span>Choisis ta classe :</span>
                <div class="container-img">
                    <img src="assets/images/mage.jpg" alt="" class="img-inscription" data-type="Mage">
                    <img src="assets/images/warrior.jpg" alt="" class="img-inscription" data-type="Warrior">
                    <img src="assets/images/healer.jpg" alt="" class="img-inscription" data-type="Healer">
                    <img src="assets/images/bot.jpg" alt="" class="img-inscription" data-type="Bot">
                </div>
                <input type="hidden" id="inscription-type">
                <button id="go" type="button" class="btn btn-success" >Go!</button>
            </div>
            <div class="modal-footer" style="display: flex; flex-direction: column;">
                <p style="color: purple">
                    <span >Le Mage :</span> ça force naturelle ajoutée à sa magie font de lui un attaquant hors paie ! (par point : +12 force ou +2 armure ou +15 en vie)
                </p>
                <p style="color: dimgrey">
                    <span >Le Warrior :</span>
                    ça résistance et son équilibre vont donner du mal à vos adversaire ! (par point : +6 force ou +15 armure ou +30 en vie)
                </p>
                <p style="color: red">
                    <span >Le Healer :</span>
                    ça tenacité en feront parler plus d'un ! Ajouté à celà sa capacité de poison !(par point : +4 force ou +10 armure ou +50 en vie)
                </p>
                <p>
                    <button id="perso-info-btn" type="button" class="btn btn-info" ><span style="font-size: 1rem">En savoir plus</span></button>
                </p>
                <div id="perso-info-div" style="display: none;">
                    <ul>
                        <li>Basique (Commun à tous sauf si modification) :</li>
                        <li>Par niveau : </li>
                        <li>De 0 à 9 : force = level  * 2 / Armure : (level-1) * 2 + ajouts / Vie: 100 + (level-1)*15 + ajouts</li>
                        <li>De 9 à 13 : force = level  * 9 + ajouts / Armure : (level-1) * 4 + ajouts / Vie: 100 + (level-1)*30 + ajouts</li>
                        <li> 13 : force = level  * 19 / Armure : (level-1) * 12 + ajouts / Vie: 100 + (level-1)*50 + ajouts</li>
                        <li> Magie : 50</li>
                    </ul>
                    <button type="button" class="btn btn-info character-info-btn" data-perso="perso-mage" ><span>Mage</span></button>
                    <button type="button" class="btn btn-info character-info-btn" data-perso="perso-warrior" ><span>Warrior</span></button>
                    <button type="button" class="btn btn-info character-info-btn" data-perso="perso-healer" ><span>Healer</span></button>
                    <button type="button" class="btn btn-info character-info-btn" data-perso="perso-healer, .perso-mage, .perso-warrior" ><span>Tous</span></button>
                    <div class="info-perso perso-mage" style="display: none; color: purple;">
                        <h3>Mage :</h3>
                        <ul>
                            <li>Par niveau : </li>
                            <li>De 0 à 9 : force = level - 1 * 10 + ajouts / Magie : 100 + (level-1) * 10 + ajouts force</li>
                            <li>De 9 à 13 : force = level - 1 * 20 + ajouts / Magie : 100 + (level-1) *20 + ajouts force</li>
                            <li> > 13 : force = level - 1 * 30 + ajouts / Magie : 100 + (level-1) *30 + ajouts force</li>
                        </ul>
                        <ul>
                            <li>Attaque :</li>
                            <li>Shield : 1 chance sur 5, active un bouclier qui réduit 90% de l'attaque. Ne se désactive qu'apres avoir été attaquer</li>
                            <li>Fireball : Nécessite de la magie : $qtyNeed = level * rand(2,4): <br>
                                -> si $totalMagie - $qtyNeed > 0 : dégat = $qtyNeed* + ajouts force<br>
                                -> sinon dégat = 2 + ajouts force
                            </li>
                            <li>Resistance poison : Nbr de Tour empoisonne / 2</li>
                        </ul>
                    </div>
                    <div class="info-perso perso-warrior" style="display: none; color: dimgrey;">
                        <h3>Warrior :</h3>
                        <ul>
                            <li>Par niveau : </li>
                            <li>De 0 à 9 : armure = 5 + (level - 1) * 15 + ajouts / Vie : 100 + (level-1) * 20 + ajouts </li>
                            <li>De 9 à 13 : armure = 5 + (level - 1) * 25 + ajouts / Vie : 100 + (level-1) * 40 + ajouts </li>
                            <li> > 13 : armure = 5 + (level - 1) * 45 + ajouts / Vie : 100 + (level-1) *65 + ajouts </li>
                        </ul>
                        <ul>
                            <li>Attaque :</li>
                            <li>charge : 1 chance sur 5, active un boost (rand(17, 40)/10) pour le prochain tour.</li>
                            <li>Punch : level* rand(5,10) + ajouts force * boost (boost = par defaut)</li>
                        </ul>
                    </div>
                    <div class="info-perso perso-healer" style="display: none; color: red;">
                        <h3>Healer :</h3>
                        <ul>
                            <li>Par niveau : </li>
                            <li>De 0 à 9 : armure = (level - 1) * 10 + ajouts / Vie : 100 + (level-1) * 35 + ajouts </li>
                            <li>De 9 à 13 : armure = (level - 1) * 15 + ajouts / Vie : 100 + (level-1) * 85 + ajouts </li>
                            <li> > 13 : armure = (level - 1) * 25 + ajouts / Vie : 100 + (level-1) * 210 + ajouts </li>
                        </ul>
                        <ul>
                            <li>Attaque :</li>
                            <li>soin : 1 chance sur 8, vieMaximale/5 + lvel * 10 * rand(4,9)</li>
                            <li>Poison : +5 tour empoisonné (cumulable), degat = level * 5 + force/5 <br>
                                ->si nbrTourEmpoissone > 10: degat = degat*(nbrTourEmpoisonne /5)  </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div id="connected" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pseudo" ></h5>
                <button type="button" id="connected-btn">
                    <span>+</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="type"></p>
                <p class="level" data-level=""></p>
                <p class="life"></p>
                <p class="experience"></p>
                <p class="strength"></p>
                <p class="armor"></p>
                <img class="img-type" src="" alt="">
            </div>
        </div>

        <div id="action" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pseudo" ></h5>
            </div>
            <div class="modal-body">
                <button id="fight" type="button" class="btn btn-success" >Combattre</button>
                <button id="moviette" type="button" class="btn btn-danger" >Ne pas assumer..</button>
            </div>
        </div>

        <div id="adversaire" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pseudo" ></h5>
            </div>
            <div class="modal-body">
                <p class="type"></p>
                <p class="level" data-level=""></p>
                <p class="life"></p>
                <p class="experience"></p>
                <p class="strength"></p>
                <p class="armor"></p>
                <img class="img-type" src="" alt="">
            </div>
        </div>

        <div id="liste" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pseudo" >Choisissez un adversaire ;)</h5>
            </div>
            <div class="modal-body">
                <table class="table table-dark">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Level</th>
                        <th scope="col">Life</th>
                        <th scope="col">Strength</th>
                        <th scope="col">Armor</th>
                    </tr>
                    </thead>
                    <?php foreach($charactersBDD as $character): ?>
                        <tr id="<?php echo str_replace(" ","",$character['name']) ?>" class="tr-list">
                            <td class="id"><?php echo $character['id'] ?></td>
                            <td class="name"><?php echo $character['name'] ?></td>
                            <td class="type"><?php echo $character['type'] ?></td>
                            <td class="level"><?php echo $character['level'] ?></td>
                            <td class="life"><?php echo $character['life'] ?></td>
                            <td class="strength"><?php echo $character['strength'] ?></td>
                            <td class="armor"><?php echo $character['armor'] ?></td>
                            <td class="experience" style="display: none;"><?php echo $character['experience'] ?></td>
                            <td class="addStrength" style="display: none;"><?php echo $character['addStrength'] ?></td>
                            <td class="addArmor" style="display: none;"><?php echo $character['addArmor'] ?></td>
                            <td class="addLife" style="display: none;"><?php echo $character['addLife'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <div id="resultat" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title titre" ></h5>
            <button type="button" id="details-btn">
                <span>+</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="message"></p>
        </div>
    </div>
    <div id="details" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title titre" ></h5>
            <button type="button" id="close-details">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        </div>
    </div>
    <input id="manche" type="hidden" value="1">
    <button id="classment-btn" type="button" class="btn btn-warning" >Classement</button>

    <div id="points-modal" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title titre" ></h5>
            <button type="button" id="close-points-modal">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Points disponibles : <span class="connected-points"></span></p>
            <input type="number" id="connected-quantity" name="quantity" min="0" max="" value="0">
            <select id="points-select">
                <option id="points-info-strength" value="addStrength"></option>
                <option id="points-info-armor" value="addArmor"></option>
                <option id="points-info-life" value="addLife"></option>
            </select>
        </div>
        <div class="modal-footer">
            <button id="setPoints" type="button" class="btn btn-primary">Valider</button>
        </div>
    </div>

    <div id="classement" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title titre" >Classement</h5>
            <button type="button" class="close-classement" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-dark">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Level</th>
                    <th scope="col">Life</th>
                    <th scope="col">Victory</th>
                    <th scope="col">Defeat</th>
                </tr>
                </thead>
                <?php foreach($charactersClassement as $character): ?>
                    <tr id="<?php echo $character['name'] ?>" class="">
                        <td class="id"><?php echo $character['numero'] ?></td>
                        <td class="name"><?php echo $character['name'] ?></td>
                        <td class="type"><?php echo $character['type'] ?></td>
                        <td class="level"><?php echo $character['level'] ?></td>
                        <td class="life"><?php echo $character['life'] ?></td>
                        <td class="victory"><?php echo $character['victory'] ?></td>
                        <td class="defeat"><?php echo $character['defeat'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>
</body>
<script>
    $('#connexion').click(function () {
        var pseudo = $('#pseudo').val();
        var path = 'game.php';
        const params = new URLSearchParams();
        params.append('pseudo', pseudo);
        params.append('fct', 'getCharacter');
        axios({
            method: 'post',
            url: path,
            data: params
        })
            .then(function (response) {
                console.log(response.data);
                if (response.data['response'] == 'success') {
                    var perso = response.data.perso;
                    $('#connected .pseudo').html(perso.name);
                    $('#connected .type').html('Type: '+perso.type);
                    $('#connected .level').html('Level: '+perso.level);
                    $('#connected .life').html('Life: '+perso.life+' (+'+perso.addLife+')');
                    $('#connected .experience').html('Xp: '+perso.experience+' / '+perso.necessaryExperience);
                    $('#connected .experience').attr('data-xp-necessary', perso.necessaryExperience);
                    $('#connected .strength').html('Strength: '+perso.strength+' (+'+perso.addStrength+')');
                    $('#connected .armor').html('Armor: '+perso.armor+' (+'+perso.addArmor+')');
                    $('#connected .level').attr('data-level', perso.level);
                    $('#connected img').attr('src', 'assets/images/'+perso.type+'.jpg');
                    $('#points-modal .connected-points').html(perso.points);
                    let strengthBy, armorBy, lifeBy;
                    $('#connected-quantity').attr('max', perso.points);
                    if (perso.type == 'Warrior') {
                        strengthBy = 6;
                        armorBy = 15;
                        lifeBy = 30;
                    }
                    else if (perso.type == 'Mage') {
                        strengthBy = 12;
                        armorBy = 2;
                        lifeBy = 15;
                    }
                    else if (perso.type == 'Healer') {
                        strengthBy = 4;
                        armorBy = 10;
                        lifeBy = 90;
                    }
                    $('#points-info-strength').html('Strength (+'+strengthBy+' par point)');
                    $('#points-info-armor').html('Armor (+'+armorBy+' par point)');
                    $('#points-info-life').html('Life (+'+lifeBy+' par point)');
                    $('#connected').show();
                    $('#liste #'+perso.name.replace(" ", "")).hide();
                    $('#liste').show();
                } else {
                    toast('#17a2b8','info','Personnages introuvable !');
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    })
    $('.tr-list').click(function () {
        var name = $(this).find('.name').html();
        var type = $(this).find('.type').html();
        var level = $(this).find('.level').html();
        var life = $(this).find('.life').html();
        var experience = $(this).find('.experience').html();
        var strength = $(this).find('.strength').html();
        var armor = $(this).find('.armor').html();
        var addLife = $(this).find('.addLife').html();
        var addArmor = $(this).find('.addArmor').html();
        var addStrength = $(this).find('.addStrength').html();
        $('#adversaire .pseudo').html(name);
        $('#adversaire .type').html('Type: '+type);
        $('#adversaire .level').html('Level: '+level);
        $('#adversaire .life').html('Life: '+life+' (+'+addLife+')');
        $('#adversaire .experience').html('Xp: '+experience);
        $('#adversaire .strength').html('Strength: '+strength+' (+'+addStrength+')');
        $('#adversaire .armor').html('Armor: '+armor+' (+'+addArmor+')');
        $('#adversaire .level').attr('data-level', level);
        $('#adversaire img').attr('src', 'assets/images/'+type+'.jpg');
        $('#liste').hide();
        $('#action').show();
        $('#adversaire').show();
    })
    $('#moviette').click(function () {
        $('#liste').show();
        $('#manche').val('1');
    $('#action').hide();
    $('#adversaire').hide();
    })
    $('#fight').click(function () {
        var manche = $('#manche').val();
        var name = $('#connected .pseudo').html();
        var adversaire = $('#adversaire .pseudo').html();
        var path = 'game.php';
        const params = new URLSearchParams();
        params.append('pseudo', name);
        params.append('adversaire', adversaire);
        params.append('fct', 'fight');
        axios({
            method: 'post',
            url: path,
            data: params
        })
            .then(function (response) {
                console.log(response.data);
                if (response.data['response'] == 'success') {
                    var message = response.data.message;
                    var levelConnected = response.data.level;
                    var levelAdversaire = response.data.adversaireLevel;
                    var lifeAdversaire = response.data.adversaireLife;
                    var winner = response.data.winner;
                    var status = response.data.status;
                    var lifeConnected = response.data.life;
                    let upClass, life, level, connectedUp, adversaireUp;
                    // Si le pserso connecté monte un niveau :
                    if (levelConnected > parseInt($('#connected .level').attr('data-level'))){
                        upClass = 'connected';
                        level = levelConnected;
                        life = lifeConnected;
                        connectedUp =true;
                        var max = $('#connected-quantity').attr('max');
                        $('#connected-quantity').attr('max', parseInt(max) + 5);
                        $('#points-modal .connected-points').html(parseInt(max) + 5);
                    }
                    else if (levelAdversaire > $('#adversaire .level').attr('data-level')){
                        upClass = 'adversaire';
                        level = levelAdversaire;
                        life = lifeAdversaire;
                        adversaireUp = true;
                    }
                    if (connectedUp || adversaireUp){
                        // Mettre à jour le front:
                        $('#'+upClass+' .level').attr('data-level', level);
                        $('#'+upClass+' .level').html('Level: '+level);
                        $('#'+upClass+' .life').html('Life: '+life);
                        $('#level-up').show();'+upClass+'
                        $('#'+upClass+' .level, #'+upClass+' .life').css({'color' : 'blue', 'font-size' : '2rem', 'font-weight' : '800'});
                        // Indiquer le level Up :
                        setTimeout(function() {
                            $('#level-up').hide();
                            $('#'+upClass+' .level, #'+upClass+' .life').css({'color' : 'black', 'font-size' : '1rem', 'font-weight' : 'normal'});
                        }, 2000);
                    }
                    // Gestion du resultat du combat:
                    $('#details .modal-body').html('');
                    let i, child;
                    var size = status.length;
                    for ( i=0; i<size; i++){
                        child = '<span>'+status[i]+'</span>';
                        $('#details .modal-body').append(child);
                    }
                    if (winner == $('#connected .pseudo').html()){
                        var experience = response.data.experience;
                        var xpNecessary =response.data.necessaryExperience;
                        $('#connected .modal-body .experience').html('Xp: '+experience+' / '+xpNecessary);
                    }
                    $('#resultat').show();
                    $('#manche').val(parseInt(manche)+1);
                    $('#resultat .titre').html('Manche n°'+manche );
                    $('#resultat .message').html(message);

                } else if (response.data['response'] == 'error') {
                    console.log(response.data);
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    })
    $('#classment-btn').click(function () {
        $('#classement').show();
    })
    $('.close-classement').click(function () {
        $('#classement').hide();
    })
    $('.container-img img').click(function () {
        $('#inscription-type').val( $(this).attr('data-type') );
        $('.container-img img').css('border', '');
        $(this).css('border', 'dashed red');
    })
    $('#inscription-btn').click(function () {
        $('#inscription').show();
    })
    $('.close-inscription').click(function () {
        $('#inscription').hide();
    })
    $('#details-btn').click(function () {
        $('#details').show();
    })
    $('#close-details').click(function () {
        $('#details').hide();
    })
    $('#connected-btn').click(function () {
        $('#points-modal').show();
    })
    $('#close-points-modal').click(function () {
        $('#points-modal').hide();
    })
    $('#perso-info-btn').click(function () {
        $('#perso-info-div').show();
    })
    $('#close-perso-info-btn').click(function () {
        $('#perso-info-div').hide();
    })
    $('.character-info-btn').click(function () {
        var cible = $(this).attr('data-perso');
        $('.info-perso').hide();
        $('.'+cible).show();
    })
    $('#go').click(function () {
        var pseudo = $('#inscription-name').val();
        var type = $('#inscription-type').val();
        var path = 'game.php';
        const params = new URLSearchParams();
        params.append('pseudo', pseudo);
        params.append('type', type);
        params.append('fct', 'addCharacter');
        if (pseudo != '' && type != ''){
            axios({
                method: 'post',
                url: path,
                data: params
            })
            .then(function (response) {
                console.log(response.data);
                if (response.data['response'] == 'success') {
                    window.location.href = "http://localhost/Cours/TP1/?pseudo="+pseudo;
                    toast('green', 'success' , response.data.message);
                }
                else{
                    toast('red', 'error' , response.data.message);
                }
            })
            .catch(function (error) {
                console.log(error);
            })
        }
        else{
            toast('#17a2b8','info','Remplis tous les champs !');
        }
    })
    $('#setPoints').click(function () {
        var quantity = $('#connected-quantity').val();
        var caracteristique = $( "#points-select").val();
        var name = $('#connected .pseudo').html();
        var path = 'game.php';
        const params = new URLSearchParams();
        params.append('quantity', quantity);
        params.append('name', name);
        params.append('caracteristique', caracteristique);
        params.append('fct', 'setPoints');
        if (quantity != 0 && caracteristique != ''){
            axios({
                method: 'post',
                url: path,
                data: params
            })
                .then(function (response) {
                    console.log(response.data);
                    if (response.data['response'] == 'success') {
                        var usedQty = response.data.usedQty;
                        var max = $('#connected-quantity').attr('max');
                        $('#connected-quantity').attr('max', parseInt(max) - usedQty);
                        $('#points-modal .connected-points').html(parseInt(max) - usedQty);
                        $('#connected-quantity').val('0');
                        let actualClass, actualQty;
                        if (caracteristique == 'addLife'){
                            actualClass = 'life';
                        }
                        else if (caracteristique == 'addStrength'){
                            actualClass = 'strength';
                        }
                        else if( caracteristique == 'addArmor'){
                            actualClass = 'armor';
                        }
                        var Upper = capitalizeFirstLetter(actualClass);
                        var actual = $('#connected .modal-body .'+actualClass).html();
                        actual = actual.split(" ")[1];
                        var qtyForPoints = $('#points-info-'+actualClass).html();
                        qtyForPoints = qtyForPoints.split("+")[1];
                        qtyForPoints = qtyForPoints.split(" ")[0];
                        var newValue = parseInt(actual) + parseInt(quantity) * parseInt(qtyForPoints);
                        $('#connected .modal-body .'+actualClass).html(Upper+': '+newValue);
                        toast('green', 'success' , response.data.message);
                    }
                    else{
                        toast('red', 'error' , response.data.message);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
        }
        else{
            toast('#17a2b8','info','Remplis tous les champs !');
        }
    })

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    // Créer une alerte :
    function toast(background, icon, title) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: background,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        Toast.fire({
            icon: icon,
            title: title
        })
    }
</script>
</html>