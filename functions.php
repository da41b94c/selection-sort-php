<?php

declare(strict_types=1);

/**
 * Returns the normalized index of the smallest value.
 *
 * Array keys are not preserved. A custom comparator may be provided with the
 * same contract as usort(): a negative value means that the left value should
 * appear before the right value.
 *
 * @template TValue
 *
 * @param array<array-key, TValue> $values
 * @param null|callable(TValue, TValue): int $comparator
 *
 * @throws InvalidArgumentException When the input array is empty.
 */
function findMin(array $values, ?callable $comparator = null): int
{
	$values = array_values($values);

	if ($values === []) {
		throw new InvalidArgumentException('Cannot find a minimum value in an empty array.');
	}

	$comparator ??= static fn (mixed $left, mixed $right): int => $left <=> $right;

	return selectionSortFindMinIndex($values, 0, $comparator);
}

/**
 * Sorts values using the selection sort algorithm.
 *
 * Array keys are not preserved and the input array is not mutated. Equal
 * values are retained. A custom comparator may be provided with the same
 * contract as usort().
 *
 * @template TValue
 *
 * @param array<array-key, TValue> $values
 * @param null|callable(TValue, TValue): int $comparator
 *
 * @return list<TValue>
 */
function selectionSort(array $values, ?callable $comparator = null): array
{
	$values = array_values($values);
	$count = count($values);

	if ($count < 2) {
		return $values;
	}

	$comparator ??= static fn (mixed $left, mixed $right): int => $left <=> $right;
	$lastIndex = $count - 1;

	for ($currentIndex = 0; $currentIndex < $lastIndex; $currentIndex++) {
		$minIndex = selectionSortFindMinIndex($values, $currentIndex, $comparator);

		if ($minIndex === $currentIndex) {
			continue;
		}

		[$values[$currentIndex], $values[$minIndex]] = [
			$values[$minIndex],
			$values[$currentIndex],
		];
	}

	return $values;
}

/**
 * Finds the minimum value index from the specified list position.
 *
 * @template TValue
 *
 * @param list<TValue> $values
 * @param callable(TValue, TValue): int $comparator
 *
 * @internal
 */
function selectionSortFindMinIndex(array $values, int $startIndex, callable $comparator): int
{
	$count = count($values);

	if ($startIndex < 0 || $startIndex >= $count) {
		throw new OutOfRangeException('The start index must reference an existing array value.');
	}

	$minIndex = $startIndex;

	for ($index = $startIndex + 1; $index < $count; $index++) {
		if ($comparator($values[$index], $values[$minIndex]) < 0) {
			$minIndex = $index;
		}
	}

	return $minIndex;
}
