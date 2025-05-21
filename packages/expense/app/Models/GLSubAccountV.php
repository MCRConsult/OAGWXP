<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLSubAccountV extends Model
{
    protected $table = 'OAG_GL_SUB_ACCOUNT_V';
    protected $connection = 'oracle';

    public function LOVResult($setName, $parent, $setValue, $text)
    {
        $flexValue = self::selectRaw('flex_value, fv_description description')
            ->where('flex_value_set_name', $setName)
            ->when($text, function ($query, $text) {
                return $query->where(function($r) use ($text) {
                    $r->where('flex_value', 'like', "${text}%")
                        ->orWhere('description', 'like', "%${text}%");
                });
            })
            ->when($parent, function ($query, $parent) {
                return $query->where(function($r) use ($parent) {
                    $r->where('dep_flex_value', $parent);
                });
            })
            ->where('summary_flag', 'N')
            ->where('enabled_flag', 'Y')
            ->orderBy('flex_value')
            ->limit(50)
            ->get();

        if ($setValue) {
            $flexAddDefault = self::selectRaw('flex_value, description')
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
            $flexValue = self::selectRaw('flex_value, description')
                ->where('flex_value_set_name', $setName)
                ->where('flex_value', $setValue)
                ->orderBy('flex_value')
                ->first();
        }
        return $flexValue;
    }


}
