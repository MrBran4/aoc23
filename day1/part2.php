<?php

// Array of stringy numbers, indexed by the number they represent.
const SPELLINGS = [
    1 => "one",
    2 => "two",
    6 => "six",
    4 => "four",
    5 => "five",
    9 => "nine",
    0 => "zero",
    3 => "three",
    7 => "seven",
    8 => "eight"
];

/**
 * Returns the number at the start of the string, or -1 if there is none.
 * @param string $in The string to check
 * @return int The number at the start of the string, or -1 if there is none.
 */
function number_at_start(string $in): int
{
    // Can't be a number if it's less than 3 chars
    if (strlen($in) < 3)
        return -1;

    // Check each number in the array
    foreach (SPELLINGS as $intval => $spelling) {

        // If the string starts with the spelling, return the index
        if (0 === strpos($in, $spelling))
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