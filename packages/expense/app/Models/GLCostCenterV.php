<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLCostCenterV extends Model
{
    protected $table = 'oaggl_security_rule_segment_v';
    protected $connection = 'oracle';

    public function LOVResult($setName, $parent, $setValue, $text)
    {
        $parent = substr($parent, -3);
        $flexValue = self::selectRaw('distinct flex_value, description')
            ->where('flex_value_set_name', $setName)
            ->when($text, function ($query, $text) {
                return $query->where(function($r) use ($text) {
                    $r->where('flex_value', 'like', "%${text}%")
                        ->orWhere('description', 'like', "%${text}%");
                });
            })
            ->when($parent, function ($query, $parent) {
                return $query->where(function($r) use ($parent) {
                    $r->where('flex_value_rule_name', $parent);
                });
            })
            ->orderBy('flex_value')
            ->limit(50)
            ->get();

        if ($setValue) {
            $flexAddDefault = self::selectRaw('distinct flex_value, description')
                ->where('flex_value_set_name', $setName)
                ->where('flex_value', $setValue)
                ->orderBy('flex_value')
                ->first();

            if ($flexAddDefault) {
                $flexValue = $flexValue->push($flexAddDefault)->unique('flex_value');
            }
        }

        return $flexValue;
    }

    public function LOVDesc($setName, $setValue, $text)
    {
        $flexValue = null;
        if ($setValue) {
            $flexValue = self::selectRaw('distinct flex_value, description')
                ->where('flex_value_set_name', $setName)
                ->where('flex_value', $setValue)
                ->orderBy('flex_value')
                ->first();
        }
        return $flexValue;
    }
}
