<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Partitions;

use InvalidArgumentException;

class TimeBasedPartitions extends Partitions
{
    public function createPartitions(int $numberOfPartitions): array
    {
        if ($numberOfPartitions <= 0) {
            throw new InvalidArgumentException("Number of parts must be greater than zero.");
        }

        $data = $this->getData();

        // Initialize the parts
        $parts = array_fill(0, $numberOfPartitions, array());
        $sums = array_fill(0, $numberOfPartitions, 0.0);

        // Sort the array in descending order
        rsort($data);

        foreach ($data as $value) {
            // Find the part with the minimum sum
            $minIndex = array_search(min($sums), $sums);

            // Add the current value to the part with the minimum sum
            $parts[$minIndex][] = $value;
            $sums[$minIndex] += $value;
        }

        // Redistribute the elements for a more even distribution
        do {
            $maxIndex = array_search(max($sums), $sums);
            $minIndex = array_search(min($sums), $sums);

            $redistributed = false;
            foreach ($parts[$maxIndex] as $key => $value) {
                // Check if moving this value to the minIndex part will bring the sums closer
                if (abs(($sums[$maxIndex] - $value) - ($sums[$minIndex] + $value)) < abs($sums[$maxIndex] - $sums[$minIndex])) {
                    // Move value from maxIndex part to minIndex part
                    unset($parts[$maxIndex][$key]);
                    $parts[$minIndex][] = $value;
                    $sums[$maxIndex] -= $value;
                    $sums[$minIndex] += $value;
                    $redistributed = true;
                    break;
                }
            }
        } while ($redistributed);

        return $parts;
    }
}
