package main

import (
	"bufio"
	"fmt"
	"os"
	"regexp"
	"strconv"
	"strings"
)

const (
	maxReds   = 12
	maxGreens = 13
	maxBlues  = 14
)

var gameRegex = regexp.MustCompile(`Game (\d+):`)
var redRegex = regexp.MustCompile(`(\d+) red`)
var greenRegex = regexp.MustCompile(`(\d+) green`)
var blueRegex = regexp.MustCompile(`(\d+) blue`)

func main() {
	reader := bufio.NewReader(os.Stdin)

	total := 0

outer:
	for {
		// Read line by line until EOF
		line, err := reader.ReadString('\n')
		if err != nil {
			break
		}

		gameNum := gameRegex.FindStringSubmatch(line)
		if len(gameNum) != 2 {
			continue
		}

		// The regex matches integers so the conversion is safe
		gameNumInt, _ := strconv.Atoi(gameNum[1])

		fmt.Printf("\nGame %d\n", gameNumInt)

		gamesPart := strings.Split(line, ":")[1]

		// Return early if there are too many of any color
		for _, game := range strings.Split(gamesPart, ";") {
			var (
				reds   = extractColor(redRegex, game)
				greens = extractColor(greenRegex, game)
				blues  = extractColor(blueRegex, game)
			)

			fmt.Printf("Game %d: %d red, %d green, %d blue\n", gameNumInt, reds, greens, blues)

			if reds > maxReds || greens > maxGreens || blues > maxBlues {
				fmt.Printf("Game %d has too many of a color\n", gameNumInt)
				continue outer
			}
		}

		total = total + gameNumInt
	}

	fmt.Println(total)
}

func extractColor(rx *regexp.Regexp, game string) int {
	parts := rx.FindStringSubmatch(game)

	if len(parts) != 2 {
		return 0
	}

	// the regex matches integers so the conversion is safe
	intval, _ := strconv.Atoi(parts[1])

	return intval
}
