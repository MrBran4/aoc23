<?php
// Initial solution to part 2

// Array of stringy numbers, indexed by the number they represent.
const SPELLINGS = [
    "zero",
    "one",
    "two",
    "three",
    "four",
    "five",
    "six",
    "seven",
    "eight",
    "nine"
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

    // Longest number is 5 chars, so we only need to check the first 5
    // (substr returns _at most_ 5 chars here)
    $tocheck = substr($in, 0, 5);

    // Check each number in the array
    foreach (SPELLINGS as $idx => $spelling) {

        // If the string starts with the spelling, return the index
        if (0 === strpos($tocheck, $spelling))
            return $idx;

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
            $thisNum = number_at_start(substr($line, $idx));
        }

        // If no match, skip updating
        if ($thisNum < 0)
            continue;

        // Always update the last char, only update the first if it's null.
        if (is_null($first))
            $first = $thisNum;

        $last = $thisNum;
    }

    echo $first . $last . "\n";

    // Skip potential invalid lines
    if (is_null($first) or is_null($last))
        continue;

    $total = $total + (int) ($first . $last);
}

fputs(STDOUT, $total);
?>