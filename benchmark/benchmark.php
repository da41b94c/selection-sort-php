<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

const BENCHMARK_ITERATIONS = 5;

$sizes = [100, 500, 1000, 2500];

printf("PHP %s, %d iterations\n\n", PHP_VERSION, BENCHMARK_ITERATIONS);
printf("%8s | %14s | %10s | %8s\n", 'values', 'selectionSort', 'sort', 'ratio');
printf("%s\n", str_repeat('-', 52));

foreach ($sizes as $size) {
	$values = [];

	for ($index = 0; $index < $size; $index++) {
		$values[] = random_int(0, $size * 10);
	}

	$selectionSortTime = measure(static fn (): array => selectionSort($values));
	$nativeSortTime = measure(static function () use ($values): array {
		$result = $values;
		sort($result);

		return $result;
	});

	printf(
		"%8d | %11.3f ms | %7.3f ms | %7.1fx\n",
		$size,
		$selectionSortTime,
		$nativeSortTime,
		$nativeSortTime > 0.0 ? $selectionSortTime / $nativeSortTime : 0.0,
	);
}

/**
 * Returns the median execution time in milliseconds.
 *
 * @param callable(): array $operation
 */
function measure(callable $operation): float
{
	$durations = [];

	for ($iteration = 0; $iteration < BENCHMARK_ITERATIONS; $iteration++) {
		$startedAt = hrtime(true);
		$operation();
		$durations[] = (hrtime(true) - $startedAt) / 1_000_000;
	}

	sort($durations);

	return $durations[intdiv(count($durations), 2)];
}
