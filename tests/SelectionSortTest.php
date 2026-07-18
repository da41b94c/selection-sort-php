<?php

declare(strict_types=1);

namespace SelectionSortPhp\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SelectionSortTest extends TestCase
{
	public function testFindMinReturnsNormalizedIndex(): void
	{
		self::assertSame(3, findMin([23, 42, 8, 4, 15, 16]));
		self::assertSame(1, findMin(['third' => 3, 'first' => 1, 'second' => 2]));
	}

	public function testFindMinSupportsCustomComparator(): void
	{
		$index = findMin(
			[3, 1, 4, 2],
			static fn (int $left, int $right): int => $right <=> $left,
		);

		self::assertSame(2, $index);
	}

	public function testFindMinRejectsEmptyInput(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('empty array');

		findMin([]);
	}

	#[DataProvider('valuesProvider')]
	public function testSelectionSort(array $input, array $expected): void
	{
		self::assertSame($expected, selectionSort($input));
	}

	public function testAssociativeKeysAreNormalized(): void
	{
		self::assertSame([1, 2, 3], selectionSort([
			'third' => 3,
			'first' => 1,
			'second' => 2,
		]));
	}

	public function testSelectionSortSupportsDescendingComparator(): void
	{
		$result = selectionSort(
			[3, 1, 4, 2],
			static fn (int $left, int $right): int => $right <=> $left,
		);

		self::assertSame([4, 3, 2, 1], $result);
	}

	public function testSelectionSortSupportsObjects(): void
	{
		$items = [
			(object) ['name' => 'Gamma', 'priority' => 30],
			(object) ['name' => 'Alpha', 'priority' => 10],
			(object) ['name' => 'Beta', 'priority' => 20],
		];

		$result = selectionSort(
			$items,
			static fn (object $left, object $right): int => $left->priority <=> $right->priority,
		);

		self::assertSame(['Alpha', 'Beta', 'Gamma'], array_column($result, 'name'));
	}

	public function testSelectionSortDoesNotMutateInput(): void
	{
		$input = [3, 1, 2];

		selectionSort($input);

		self::assertSame([3, 1, 2], $input);
	}

	public function testSelectionSortPerformsExpectedNumberOfComparisons(): void
	{
		$comparisons = 0;

		selectionSort(
			[4, 3, 2, 1],
			static function (int $left, int $right) use (&$comparisons): int {
				$comparisons++;

				return $left <=> $right;
			},
		);

		self::assertSame(6, $comparisons);
	}

	public static function valuesProvider(): iterable
	{
		yield 'empty' => [[], []];
		yield 'single value' => [[7], [7]];
		yield 'sample' => [[23, 42, 8, 4, 15, 16], [4, 8, 15, 16, 23, 42]];
		yield 'duplicates' => [[3, 1, 3, 2, 1], [1, 1, 2, 3, 3]];
		yield 'negative numbers' => [[0, -10, 5, -10, 2], [-10, -10, 0, 2, 5]];
		yield 'strings' => [['pear', 'apple', 'pear', 'banana'], ['apple', 'banana', 'pear', 'pear']];
		yield 'already sorted' => [[1, 2, 3, 4], [1, 2, 3, 4]];
		yield 'reverse sorted' => [[4, 3, 2, 1], [1, 2, 3, 4]];
	}
}
