<?php


function valueAt($board, $coord)
{
    return $board[$coord[0]][$coord[1]];
}

function hasValue($board, $coord)
{
    return ($board[$coord[0]][$coord[1]] != 0);
}

function rowValues($board, $coord)
{
    $values = array_values(array_unique($board[$coord[0]]));
    sort($values);

    return $values;
}

function colValues($board, $coord)
{
    $col = [];
    foreach ($board as $row) {
        $col[] = $row[$coord[1]];
    }
    $values = array_values(array_unique($col));
    sort($values);

    return $values;
}

function coordPairs($coord)
{
    $result = [];
    $y_range = range($coord[1], $coord[1] + 2);
    foreach ((range($coord[0], $coord[0] + 2)) as $x) {
        foreach ($y_range as $y) {
            $result[] = [$x, $y];
        }
    }

    return $result;
}

function blockHelper($coord)
{
    $result = [];
    foreach ($coord as $item) {
        $result[] = ((int) ($item / 3)) * 3;
    }

    return $result;
}

function blockValues($board, $coord)
{
    $result = [];
    $coord_pairs = coordPairs(blockHelper($coord));
    foreach ($coord_pairs as $coord) {
        $result[] = $board[$coord[0]][$coord[1]];
    }
    $values = array_values(array_unique($result));
    sort($values);

    return $values;
}

function validValuesFor($board, $coord)
{
    $all_values = [1, 2, 3, 4, 5, 6, 7, 8, 9,];
    if (hasValue($board, $coord)) {
        return [];
    }
    $union = array_unique(array_merge(rowValues($board, $coord),
        colValues($board, $coord), blockValues($board, $coord)));

    return array_values(array_diff($all_values, $union));
}

function filled($board)
{
    foreach ($board as $row) {
        if (in_array(0, $row)) {
            return false;
        }
    }

    return true;
}

function rows($board)
{
    $result = [];
    for ($i = 0; $i<9; $i++) {
        $result[] = rowValues($board, [$i, 0]);
    }

    return $result;
}

function cols($board)
{
    $result = [];
    for ($i = 0; $i<9; $i++) {
        $result[] = colValues($board, [0, $i]);
    }

    return $result;
}

function blocks($board)
{
    $result = [];
    $origins = [[0, 0,], [0, 3,], [0, 6,], [3, 0,], [3, 3,], [3, 6,], [6, 0,], [6, 3,], [6, 6,],];
    foreach ($origins as $origin) {
        $result[] = blockValues($board, $origin);
    }

    return $result;
}

function validRows($board)
{
    $all_values = [1, 2, 3, 4, 5, 6, 7, 8, 9,];
    foreach ($board as $row) {
        sort($row);
        if ($row != $all_values) {
            return false;
        }
    }

    return true;
}

function validCols($board)
{
    $all_values = [1, 2, 3, 4, 5, 6, 7, 8, 9,];
    $cols = cols($board);
    foreach ($cols as $row) {
        sort($row);
        if ($row != $all_values) {
            return false;
        }
    }

    return true;
}

function validBlocks($board)
{
    $all_values = [1, 2, 3, 4, 5, 6, 7, 8, 9,];
    $blocks = blocks($board);
    foreach ($blocks as $row) {
        sort($row);
        if ($row != $all_values) {
            return false;
        }
    }

    return true;
}

function validSolution($board)
{
    return (validRows($board) && validCols($board) && validBlocks($board));
}

function setValueAt($board, $coord, $value)
{
    $board[$coord[0]][$coord[1]] = $value;

    return $board;
}

function findEmptyPoint($board)
{
    for ($i = 0; $i<9; $i++) {
        for ($j = 0; $j<9; $j++) {
            if ($board[$i][$j] == 0) {
                return [$i, $j];
            }
        }
    }
}

function solve($board)
{ // get empty spaces, find one with one choice, set it , rinse and repeat
    while ( ! filled($board) && ! validSolution($board)) {
        for ($i = 0; $i<9; $i++) {
            for ($j = 0; $j<9; $j++) {
                if($board[$i][$j] == 0){
                    $valid = validValuesFor($board, [$i, $j]);
                    if(count($valid) == 1){
                        $board[$i][$j] = $valid[0];
                    }
                }
            }
        }
    }
    return $board;
}
  $solved =  solve([
        [0, 5, 6, 9, 0, 7, 4, 0, 0],
        [0, 8, 1, 0, 4, 0, 0, 0, 0],
        [0, 0, 0, 0, 1, 5, 0, 9, 0],
        [0, 0, 0, 0, 0, 3, 8, 5, 7],
        [8, 4, 0, 0, 6, 0, 0, 2, 3],
        [7, 3, 9, 2, 0, 0, 0, 0, 0],
        [0, 6, 0, 5, 8, 0, 0, 0, 0],
        [0, 0, 0, 0, 7, 0, 3, 6, 0],
        [0, 0, 8, 3, 0, 6, 5, 7, 0],
    ]);
echo (validSolution($solved));
print_r($solved);

