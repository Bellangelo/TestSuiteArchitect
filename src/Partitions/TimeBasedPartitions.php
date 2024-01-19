<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Partitions;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use InvalidArgumentException;

class TimeBasedPartitions extends Partitions
{
    public function createPartitions(int $numberOfPartitions): array
    {
        if ($numberOfPartitions <= 1) {
            throw new InvalidArgumentException("Number of parts must be greater than one.");
        }

        $data = $this->getData()->toArray();

        // Initialize the parts
        $parts = array_fill(0, $numberOfPartitions, array());
        $sums = array_fill(0, $numberOfPartitions, 0.0);

        usort($data, function (TestTimer $a, TestTimer $b) {
            return $b->getElapsedTime() - $a->getElapsedTime();
        });

        foreach ($data as $item) {
            // Find the part with the minimum sum
            $minIndex = array_search(min($sums), $sums);

            // Add the current item to the part with the minimum sum
            $parts[$minIndex][] = $item;
            $sums[$minIndex] += $item->getElapsedTime();
        }

        // Redistribute the elements for a more even distribution
        do {
            $maxIndex = array_search(max($sums), $sums);
            $minIndex = array_search(min($sums), $sums);

            $redistributed = false;

            /**
             * @var TestTimer $item
             */
            foreach ($parts[$maxIndex] as $key => $item) {
                // Check if moving this item to the minIndex part will bring the sums closer
                if (abs(($sums[$maxIndex] - $item->getElapsedTime()) - ($sums[$minIndex] + $item->getElapsedTime())) < abs($sums[$maxIndex] - $sums[$minIndex])) {
                    // Move item from maxIndex part to minIndex part
                    unset($parts[$maxIndex][$key]);
                    $parts[$minIndex][] = $item;
                    $sums[$maxIndex] -= $item->getElapsedTime();
                    $sums[$minIndex] += $item->getElapsedTime();
                    $redistributed = true;
                    break;
                }
            }
        } while ($redistributed);

        return $parts;
    }
}
