<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Partitions;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;

class LoadBalancingPartitions extends Partitions
{
    public function createPartitions(int $numberOfPartitions): array
    {
        $array = $this->getData()->toArray();
        $totalElements = count($array);
        $result = [];

        $basePartSize = (int) floor($totalElements / $numberOfPartitions);
        // Extra items to distribute
        $extraElements = $totalElements % $numberOfPartitions;

        $startIndex = 0;

        for ($i = 0; $i < $numberOfPartitions; ++$i) {
            // Calculate the size of the current part
            $partSize = $basePartSize + ($i < $extraElements ? 1 : 0);

            $result[] = array_slice($array, $startIndex, $partSize);

            $startIndex += $partSize;
        }

        return $result;
    }
}
