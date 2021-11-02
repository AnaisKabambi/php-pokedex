<?php


function getPokemon($input)
{


    $data = file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $input);

    if ( $data=== false )
    {
        echo $_SERVER['QUERY_STRING'];
        toHome();
    }else{
        // $f = explode("fail", $_SERVER['REQUEST_URI'])[1]
        // echo (explode("fail", $_SERVER['REQUEST_URI'])[1]);
        $parsedData = json_decode($data, JSON_OBJECT_AS_ARRAY);
        return (array_values($parsedData));
    }

}

function getEvolutionUrl($id)
{
    $data = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $id);
    $parsedData = json_decode($data, JSON_OBJECT_AS_ARRAY);
    return (array_values($parsedData)[4]['url']);
}

function getEvolutions($url)
{
    $data = file_get_contents($url);
    $parsedData = json_decode($data, JSON_OBJECT_AS_ARRAY);
    return (array_values($parsedData));
}
?>

    <form action="" method="get">
        <input type="text" name="poke" required>
        <input type="submit" value="Search Pokemon">
    </form>

<?php
if (isset($_GET["poke"])) {
    $poke = $_GET["poke"];
    $poke = str_replace(".", "", $poke);
    $poke = str_replace(" ", "-", $poke);
    $res = getPokemon($poke);

    $movesLength = (count($res[9]));
    $moves = [];
    if ($movesLength >= 4) {
        for ($i = 0; $i < 4; $i++) {
            array_push($moves, $res[9][rand(0, $movesLength - 1)]['move']['name']);
        }
    } else {
        for ($j = 0; $j < $movesLength; $j++) {

            array_push($moves, $res[9][$j]['move']['name']);
        }
    }

    $name = $res[2][0]['name'];
    $picUrl = $res[14]['front_default'];
} else {
    $res = getPokemon(25);

    $movesLength = (count($res[9]));
    $moves = [];
    if ($movesLength >= 4) {
        for ($i = 0; $i < 4; $i++) {
            array_push($moves, $res[9][rand(0, $movesLength - 1)]['move']['name']);
        }
    } else {
        for ($j = 0; $j < $movesLength; $j++) {

            array_push($moves, $res[9][$j]['move']['name']);
        }
    }

    $name = $res[2][0]['name'];
    $picUrl = $res[14]['front_default'];
}
?>

    <div>
        <?php
        if (isset($name)) {
            echo "Name: " . $name . "<br>";
        }
        if (isset($picUrl)) {
            echo "<img src='$picUrl' alt=''>";
        }

        ?>
        <ul>
            <?php
            if (isset($moves)) {
                foreach ($moves as $move) {
                    echo "<li>" . $move . "</li>";
                }
            }
            ?>
        </ul>
    </div>


    <div>
    <h4>Evolution</h4>
<?php
if (isset($poke)) {
    $url = getEvolutionUrl($poke);
    $evolutions = getEvolutions($url);

    $pokePrevious = getPokemon($evolutions[1]['species']['name']);
    $pokePreviousImg = $pokePrevious[14]['front_default'];
    $pokePreviousName = $pokePrevious[2][0]['name'];
    echo "<img src='$pokePreviousImg' alt='$pokePreviousName' onclick='document.location.href=`/index.php?poke=$pokePreviousName`;return false;' >";

    if (count($evolutions[1]['evolves_to']) > 0) {
        if ((count($evolutions[1]['evolves_to'][0]['evolves_to']) > 0)) {
            $pokeCurrent = getPokemon($evolutions[1]['evolves_to'][0]['species']['name']);
            $pokeCurrentImg = $pokeCurrent[14]['front_default'];
            $pokeCurrentName = $pokeCurrent[2][0]['name'];
            echo "<img src='$pokeCurrentImg' alt='$pokeCurrentName' onclick='document.location.href=`/index.php?poke=$pokeCurrentName`;return false;' >";

            $pokeNext = getPokemon($evolutions[1]['evolves_to'][0]['evolves_to'][0]['species']['name']);
            $pokeNextImg = $pokeNext[14]['front_default'];
            $pokeNextName = $pokeNext[2][0]['name'];
            echo "<img src='$pokeNextImg' alt='$pokeNextName' onclick='document.location.href=`/index.php?poke=$pokeNextName`;return false;'>";
        } else {
            $pokeCurrent = getPokemon($evolutions[1]['evolves_to'][0]['species']['name']);
            $pokeCurrentImg = $pokeCurrent[14]['front_default'];
            $pokeCurrentName = $pokeCurrent[2][0]['name'];
            echo "<img src='$pokeCurrentImg' alt='$pokeCurrentName' onclick='document.location.href=`/index.php?poke=$pokeCurrentName`;return false;' >";
        }
    }
} else {
    $url = getEvolutionUrl(25);
    $evolutions = getEvolutions($url);

    $pokePrevious = getPokemon($evolutions[1]['species']['name']);
    $pokePreviousImg = $pokePrevious[14]['front_default'];
    $pokePreviousName = $pokePrevious[2][0]['name'];
    echo "<img src='$pokePreviousImg' alt='$pokePreviousName' onclick='document.location.href=`/index.php?poke=$pokePreviousName`;return false;' >";

    $pokeCurrent = getPokemon($evolutions[1]['evolves_to'][0]['species']['name']);
    $pokeCurrentImg = $pokeCurrent[14]['front_default'];
    $pokeCurrentName = $pokeCurrent[2][0]['name'];
    echo "<img src='$pokeCurrentImg' alt='$pokeCurrentName' onclick='document.location.href=`/index.php?poke=$pokeCurrentName`;return false;' >";

    $pokeNext = getPokemon($evolutions[1]['evolves_to'][0]['evolves_to'][0]['species']['name']);
    $pokeNextImg = $pokeNext[14]['front_default'];
    $pokeNextName = $pokeNext[2][0]['name'];
    echo "<img src='$pokeNextImg' alt='$pokeNextName' onclick='document.location.href=`/index.php?poke=$pokeNextName`;return false;'>";
}

?>
    </div>
<?php
