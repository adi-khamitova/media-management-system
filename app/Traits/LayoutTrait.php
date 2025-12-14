<?php

declare(strict_types=1);

namespace App\Traits;

trait LayoutTrait
{
    public function transformLayout(array $layout): array
    {
        $result = [];
        foreach ($layout as $tile) {
            $resultTile = [
                'x' => $tile[0],
                'y' => $tile[1],
                'z' => $tile[2],
                'type' => $tile[3],
                'clickable' => true
            ];

            $upperTiles = array_filter($layout, static function ($item) use ($tile) {
                if (
                    $item[2] === $tile[2] + 1
                    && $item[0] === $tile[0]
                    && $item[1] === $tile[1]
                ) {
                    return $item;
                }
                return null;
            });

            $leftTiles = array_filter($layout, static function ($item) use ($tile) {
                if (
                    $item[2] === $tile[2]
                    && $item[1] === $tile[1] - 1
                    && $item[0] === $tile[0]
                ) {
                    return $item;
                }
                return null;
            });

            $rightTiles = array_filter($layout, static function ($item) use ($tile) {
                if (
                    $item[1] === $tile[1] + 1
                    && $item[2] === $tile[2]
                    && $item[0] === $tile[0]
                ) {
                    return $item;
                }
                return null;
            }
            );

            if (count($upperTiles) > 0 || (count($leftTiles) > 0 && count($rightTiles) > 0)) {
                $resultTile['clickable'] = false;
            }
            $result[] = $resultTile;
        }
        return $result;
    }

    public function getMatchCount(array $board): int
    {
        $cnt = 0;
        $tileTypes = array_unique(array_map(static fn($item) => $item['type'], $board));
        foreach ($tileTypes as $tileType) {
            $tiles = array_filter($board, static function ($item) use ($tileType) {
                if ($item['type'] === $tileType && $item['clickable']) {
                    return $item;
                }
                return null;
            });

            $cnt += (int)(count($tiles) / 2);
        }
        return $cnt;
    }
}
