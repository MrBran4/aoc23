use common::{get_neighbours, Element};

mod common;

fn main() {
    // Read from stdin
    let lines = common::read_all_lines();

    // Turn into a grid of elements
    let grid = common::parse_lines(lines);

    // Extract all numeric blocks
    let blocks = common::find_blocks(&grid);

    // Sum the blocks
    let total: i32 = blocks
        .iter()
        .filter(|b| {
            get_neighbours(b, &grid)
                .iter()
                .filter(|el| match el {
                    Element::Symbol() => true,
                    _ => false,
                })
                .count()
                > 0
        })
        .map(|b| b.value)
        .sum();

    println!("Total: {}", total)
}
