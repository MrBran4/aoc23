<?php
// Optimisation: Switch key and value in the arrays

// Arrays of stringy numbers, indexed by the number they represent.
const THREES = [
    "one" => 1,
    "two" => 2,
    "six" => 6,
];

const FOURS = [
    "four" => 4,
    "five" => 5,
    "nine" => 9,
];

const FIVES = [
    "three" => 3,
    "seven" => 7,
    "eight" => 8,
];

/**
 * Returns the number at the start of the string, or -1 if there is none.
 * @param string $in The string to check
 * @return int The number at the start of the string, or -1 if there is none.
 */
function number_at_start(string $in): int
{
    $maxlen = strlen($in);

    // Can't be a number if it's less than 3 chars
    if ($maxlen < 3) {
        return -1;
    }

    // Check each 3 letter number
    $check = substr($in, 0, 3);

    foreach (THREES as $spelling => $intval) {
        // If the string is the spelling, return the number
        if ($check === $spelling)
            return $intval;
    }

    // Skip if it's less than 4 chars
    if ($maxlen < 4)
        return -1;

    // Check each 4 letter number
    $check = substr($in, 0, 4);
    foreach (FOURS as $spelling => $intval) {
        // If the string is the spelling, return the number
        if ($check === $spelling)
            return $intval;
    }

    // Skip if it's less than 5 chars
    if ($maxlen < 5)
        return -1;

    // Check each 5 letter number
    foreach (FIVES as $spelling => $intval) {
        // If the string is the spelling, return the number
        if ($in === $spelling)
            return $intval;
    }

    // No number found
    return -1;
}

$total = 0;

// Process the input line by line
while (FALSE !== ($line = fgets(STDIN))) {
    $first = NULL;
    $last = NULL;

    // Loop through each char
    foreach (str_split($line) as $idx => $char) {
        $thisNum = -1;

        // If it's a number, treat it as such and update the vars
        if (is_numeric($char)) {
            $thisNum = (int) $char;
        } else {
            // Otherwise, check if it's a number spelled out
            // longest word is 5 chars so no point going longer
            $thisNum = number_at_start(substr($line, $idx, 5));
        }

        // If no match, skip updating
        if ($thisNum < 0)
            continue;

        // Always update the last char, only update the first if it's null.
        if (is_null($first))
            $first = $thisNum;

        $last = $thisNum;
    }

    // Skip potential invalid lines
    if (is_null($first) or is_null($last))
        continue;

    $total = $total + (int) ($first . $last);
}

fputs(STDOUT, $total);
?>