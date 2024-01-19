<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Partitions;

use InvalidArgumentException;

class TimeBasedPartitions extends Partitions
{
    public function createPartitions(int $numberOfPartitions): array
    {
        if ($numberOfPartitions <= 1) {
            throw new InvalidArgumentException("Number of parts must be greater than one.");
        }

        $data = $this->getData();

        // Initialize the parts
        $parts = array_fill(0, $numberOfPartitions, array());
        $sums = array_fill(0, $numberOfPartitions, 0.0);

        // Convert the data to an array of ['filename' => ..., 'time' => ...] and sort by time descending
        $dataArray = [];
        foreach ($data as $filename => $time) {
            $dataArray[] = ['filename' => $filename, 'time' => $time];
        }
        usort($dataArray, function ($a, $b) {
            return $b['time'] - $a['time'];
        });

        foreach ($dataArray as $item) {
            // Find the part with the minimum sum
            $minIndex = array_search(min($sums), $sums);

            // Add the current item to the part with the minimum sum
            $parts[$minIndex][] = $item;
            $sums[$minIndex] += $item['time'];
        }

        // Redistribute the elements for a more even distribution
        do {
            $maxIndex = array_search(max($sums), $sums);
            $minIndex = array_search(min($sums), $sums);

            $redistributed = false;
            foreach ($parts[$maxIndex] as $key => $item) {
                // Check if moving this item to the minIndex part will bring the sums closer
                if (abs(($sums[$maxIndex] - $item['time']) - ($sums[$minIndex] + $item['time'])) < abs($sums[$maxIndex] - $sums[$minIndex])) {
                    // Move item from maxIndex part to minIndex part
                    unset($parts[$maxIndex][$key]);
                    $parts[$minIndex][] = $item;
                    $sums[$maxIndex] -= $item['time'];
                    $sums[$minIndex] += $item['time'];
                    $redistributed = true;
                    break;
                }
            }
        } while ($redistributed);

        return $parts;
    }
}
