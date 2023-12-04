use common::{get_neighbours, Block, Element};

mod common;

fn main() {
    // Read from stdin
    let lines = common::read_all_lines();

    // Turn into a grid of elements
    let grid = common::parse_lines(lines);

    // Extract all numeric blocks
    let blocks = common::find_blocks(&grid);

    // Find all symbols
    let symbols = common::find_symbols(&grid);

    let mut sum = 0;

    // Loop through all symbols, if they neighbour exactly two blocks then multiply the blocks.
    for (row, col) in symbols {
        let neighbours = get_neighbours(
            &Block {
                row,
                start: col,
                end: col,
                value: 0,
            },
            &grid,
        );

        let mut block_count = 0;
        let mut block_product = 1;

        if block_count == 2 {
            sum += block_product;
        }
    }
}
