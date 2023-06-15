<?php
// Función para crear una baraja de cartas
function crearBaraja()
{
    $palos = array('Picas', 'Corazones', 'Tréboles', 'Diamantes');
    $valores = array('As', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K');
    $baraja = array();

    foreach ($palos as $palo) {
        foreach ($valores as $valor) {
            $carta = array(
                'valor' => $valor,
                'palo' => $palo
            );
            array_push($baraja, $carta);
        }
    }

    return $baraja;
}

// Función para barajar la baraja de cartas
function barajarBaraja(&$baraja)
{
    shuffle($baraja);
}

// Función para repartir 5 cartas al jugador
function repartirCartas(&$baraja)
{
    $mano = array();

    for ($i = 0; $i < 5; $i++) {
        $carta = array_shift($baraja);
        array_push($mano, $carta);
    }

    return $mano;
}

// Función para evaluar la mejor combinación de cartas
function evaluarCombinacion($mano)
{
    // Ordenar las cartas por valor ascendente
    usort($mano, function ($a, $b) {
        return valorCarta($a['valor']) - valorCarta($b['valor']);
    });

    // Verificar si hay una escalera
    $esEscalera = true;
    $valorAnterior = valorCarta($mano[0]['valor']);
    for ($i = 1; $i < 5; $i++) {
        if (valorCarta($mano[$i]['valor']) != $valorAnterior + 1) {
            $esEscalera = false;
            break;
        }
        $valorAnterior = valorCarta($mano[$i]['valor']);
    }

    // Verificar si hay un color (todas las cartas del mismo palo)
    $esColor = true;
    $paloAnterior = $mano[0]['palo'];
    for ($i = 1; $i < 5; $i++) {
        if ($mano[$i]['palo'] != $paloAnterior) {
            $esColor = false;
            break;
        }
        $paloAnterior = $mano[$i]['palo'];
    }

    // Mostrar las cartas en la mano
    echo "Cartas en la mano:\n";
    for ($i = 0; $i < 5; $i++) {
        echo ($i + 1) . ". " . $mano[$i]['valor'] . ' de ' . $mano[$i]['palo'] . "\n";
    }

    // Solicitar al jugador que descarte cartas
    echo "Elige las cartas que deseas descartar (separa los números por comas): ";
    $input = readline();
    $descartadas = array_map('intval', explode(',', $input));

    // Descartar las cartas seleccionadas
    foreach ($descartadas as $indice) {
        $mano[$indice - 1] = array_shift($baraja);
    }

    // Evaluar la nueva mano después de descartar cartas
    evaluarCombinacion($mano);
}

// Función auxiliar para asignar valores numéricos a las cartas
function valorCarta($valor)
{
    if ($valor == 'As') {
        return 1;
    } elseif ($valor == 'J') {
        return 11;
    } elseif ($valor == 'Q') {
        return 12;
    } elseif ($valor == 'K') {
        return 13;
    } else {
        return intval($valor);
    }
}

// Juego de Poker
$baraja = crearBaraja();
barajarBaraja($baraja);
$mano = repartirCartas($baraja);
evaluarCombinacion($mano);
?>
