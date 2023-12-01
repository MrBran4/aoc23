<?php
$total = 0;

// Process the input line by line
while (FALSE !== ($line = fgets(STDIN))){
    $first = NULL;
    $last = NULL;

    // Loop through each char
    foreach( str_split($line) as $char){

        // if it's not a number we don't care
        if (!is_numeric($char))
            continue;

        // Always update the last char, only update the first if it's null.
        if (is_null($first))
            $first = (int) $char;

        $last = (int) $char;
    }

    // Skip potential invalid lines
    if (is_null($first) or is_null($last))
        continue;

    $total = $total + (int) ($first . $last);
}

fputs(STDOUT, $total);
?>