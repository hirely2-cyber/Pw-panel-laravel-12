<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ItemDatabase
{
    protected array $items = [];

    public function __construct()
    {
        $this->items = Cache::remember('item_database_names_v2', 86400, function () {
            $items = [];

            // Primary: item_names.json (extracted from elements.data, covers all IDs)
            $jsonPath = storage_path('app/item_names.json');
            if (file_exists($jsonPath)) {
                $data = json_decode(file_get_contents($jsonPath), true);
                if (is_array($data)) {
                    foreach ($data as $id => $name) {
                        $items[(int) $id] = $name;
                    }
                }
            }

            // Fallback/supplement: pwi.dat (text file, line index = item ID, 0-29999)
            $datPath = storage_path('app/pwi.dat');
            if (file_exists($datPath) && ($handle = fopen($datPath, 'r')) !== false) {
                $id = 0;
                while (($line = fgets($handle)) !== false && $id < 30001) {
                    if (!isset($items[$id])) {
                        $name = trim(strip_tags($line));
                        $name = html_entity_decode($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $name = trim($name);
                        if ($name !== '' && $name !== 'Item not Found') {
                            $items[$id] = $name;
                        }
                    }
                    $id++;
                }
                fclose($handle);
            }

            return $items;
        });
    }

    public function getName(int $id): ?string
    {
        return $this->items[$id] ?? null;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function forItems(array $itemArrays): array
    {
        $lookup = [];
        foreach ($itemArrays as $items) {
            foreach ($items as $item) {
                $id = $item['id'] ?? 0;
                if ($id > 0 && isset($this->items[$id])) {
                    $lookup[$id] = $this->items[$id];
                }
            }
        }
        return $lookup;
    }

    public static function clearCache(): void
    {
        Cache::forget('item_database_names_v2');
    }
}
