use std::io::BufRead;

pub fn read_all_lines() -> Vec<String> {
    let std = std::io::stdin();

    let mut lines = Vec::new();

    for line in std.lock().lines() {
        match line {
            Ok(line) => lines.push(line),
            Err(e) => println!("Error: {}", e),
        }
    }

    lines
}

/// A single element of a line.
/// Can be a numeric value, a filler or a symbol.
/// For example, the line "467..11*4" would be represented as:
/// [Numeric(4), Numeric(6), Numeric(7), Filler(), Filler(), Numeric(1), Numeric(1), Symbol(), Numeric(4)]
#[derive(Debug, Clone)]
pub enum Element {
    Numeric(i8),
    Filler(),
    Symbol(),
}

pub type Grid = Vec<Vec<Element>>;

/// Converts a string to a vector of elements.
/// Any numeric character is converted to a numeric element,
/// any dot is converted to a filler element,
/// any other character is assumed to be a symbol.
fn parse_line(line: &str) -> Vec<Element> {
    line.chars()
        .map(|c| match c {
            '0'..='9' => Element::Numeric(c.to_digit(10).unwrap() as i8),
            '.' => Element::Filler(),
            _ => Element::Symbol(), // everything else is a symbol.
        })
        .collect()
}

/// Converts a vector of lines to a 2d.
/// We assume that the lines are all of the same length.
pub fn parse_lines(lines: Vec<String>) -> Grid {
    lines.iter().map(|line| parse_line(line)).collect()
}

/// A contiguous block of numeric elements.
pub struct Block {
    pub row: usize,
    pub start: usize,
    pub end: usize,
    pub value: i32,
}

pub fn find_symbols(grid: &Grid) -> Vec<(usize, usize)> {
    let mut symbols = Vec::new();

    for (row, line) in grid.iter().enumerate() {
        for (col, element) in line.iter().enumerate() {
            match element {
                Element::Symbol() => symbols.push((row, col)),
                _ => {}
            }
        }
    }

    symbols
}

/// Finds all the contiguous blocks of numeric elements in a grid.
/// Returns a vector of blocks.
pub fn find_blocks(grid: &Grid) -> Vec<Block> {
    let mut blocks = Vec::new();

    // Loop through all rows in the grid
    for (row, line) in grid.iter().enumerate() {
        // This'll store the elements in the current block
        let mut elements_in_block = Vec::new();
        let mut block_started_at: Option<usize> = None;

        // Loop through all chars in the line
        for (idx, element) in line.iter().enumerate() {
            // If it's a numeric element, add it to the block
            match element {
                Element::Numeric(n) => {
                    elements_in_block.push(n);
                    // If we haven't started a block yet, start it now.
                    if block_started_at.is_none() {
                        block_started_at = Some(idx);
                    }

                    continue;
                }
                _ => {}
            }

            // If it's not a numeric element, we've reached the end of the block
            // (or no block at all)
            if elements_in_block.len() == 0 {
                // If we don't have a block to end, we can just continue.
                continue;
            }

            // If we do have a block to end, we need to add it to the list of blocks
            // and reset the elements_in_block vector.
            blocks.push(Block {
                row,
                start: block_started_at.unwrap(),
                end: idx - 1,
                // The value is the concatenation of all the numeric elements in the block, parsed as an integer.
                value: elements_in_block
                    .iter()
                    .map(|n| n.to_string())
                    .collect::<String>()
                    .parse::<i32>()
                    .unwrap(),
            });

            block_started_at = None;
            elements_in_block = Vec::new();
        }

        // If we have a block that hasn't been ended yet, we need to end it.
        // If it's not a numeric element, we've reached the end of the block
        // (or no block at all)
        if elements_in_block.len() == 0 {
            // If we don't have a block to end, we can just continue.
            continue;
        }

        // If we do have a block to end, we need to add it to the list of blocks
        // and reset the elements_in_block vector.
        blocks.push(Block {
            row,
            start: block_started_at.unwrap(),
            end: line.len() - 1,
            // The value is the concatenation of all the numeric elements in the block, parsed as an integer.
            value: elements_in_block
                .iter()
                .map(|n| n.to_string())
                .collect::<String>()
                .parse::<i32>()
                .unwrap(),
        });
    }

    blocks
}

pub fn elements_in_rect(
    top_left: (usize, usize),
    bottom_right: (usize, usize),
    grid: &Grid,
) -> Vec<Element> {
    let mut elements = Vec::new();

    for row in top_left.0..=bottom_right.0 {
        for col in top_left.1..=bottom_right.1 {
            elements.push(grid[row][col].clone());
        }
    }

    elements
}

pub fn get_neighbours(block: &Block, grid: &Grid) -> Vec<Element> {
    let line_length = grid[0].len();

    elements_in_rect(
        (
            block.row.max(1) - 1,   // Top left X
            block.start.max(1) - 1, // Top left Y
        ),
        (
            block.row.min(line_length - 2) + 1, // Bottom right X
            block.end.min(grid.len() - 2) + 1,  // Bottom right Y
        ),
        &grid,
    )
}
