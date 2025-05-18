<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class COAListV extends Model
{
    protected $table = 'OAG_COA_LIST_V';
    protected $connection = 'oracle';

    public function getDefaultSetName()
    {
        $defaultValueSetName = (object)[];
        $defaultValueSetName->segment1 = 'OAG_GL_DEPARTMENT';
        $defaultValueSetName->segment2 = 'OAG_GL_COST_CENTER';
        $defaultValueSetName->segment3 = 'OAG_GL_BUDGET_YEAR';
        $defaultValueSetName->segment4 = 'OAG_GL_BUDGET_SOURCE';
        $defaultValueSetName->segment5 = 'OAG_GL_BUDGET_PLAN';
        $defaultValueSetName->segment6 = 'OAG_GL_BUDGET_PRODUCT';
        $defaultValueSetName->segment7 = 'OAG_GL_BUDGET_ACTIVITY';
        $defaultValueSetName->segment8 = 'OAG_GL_BUDGET_TYPE';
        $defaultValueSetName->segment9 = 'OAG_GL_BUDGET_CODE';
        $defaultValueSetName->segment10 = 'OAG_GL_ACCOUNT';
        $defaultValueSetName->segment11 = 'OAG_GL_SUB_ACCOUNT';
        $defaultValueSetName->segment12 = 'OAG_GL_RESERVE_1';
        $defaultValueSetName->segment13 = 'OAG_GL_RESERVE_2';

        return $defaultValueSetName;
    }

    public function LOVResult($setName, $setValue, $text)
    {
        $flexValue = self::selectRaw('flex_value, description')
            ->where('flex_value_set_name', $setName)
            ->when($text, function ($query, $text) {
                return $query->where(function($r) use ($text) {
                    $r->where('flex_value', 'like', "${text}%")
                        ->orWhere('description', 'like', "%${text}%");
                });
            })
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
