<?php

namespace App\Helpers;

class Helper
{

    public static function resolveOrder(string $model, array $conditions, $order): void
    {
        $query = $model::query();

        if (count($conditions) > 0) {
            foreach ($conditions as $field => $value) {
                $query->where($field, $value);
            }
        }
        $existingDatas = $query->orderBy('order', 'asc')
            ->get();

        foreach ($existingDatas as $data) {
            if ($data->order >= $order) {
                $data->order++;
                $data->save();
            }
        }
    }
}
