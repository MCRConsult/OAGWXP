<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    protected $table = 'oagwxp_permissions';
    protected $connection = 'oracle_oagwxp';
    protected $appends = ['perm_group_title', 'perm_group_color'];
    protected $group_meaning = [
        "requisition"   =>  "เอกสารส่งเบิก",
        "invoice"       =>  "เอกสารขอเบิก",
        "history"       =>  "ประวัติการอินเตอร์เฟซ",
        "report"        =>  'รายงาน',
        "setting"       =>  'การตั้งค่า'
    ];
    protected $group_color = [
        "requisition"   =>  "e55050",
        "invoice"       =>  "ffa725",
        "history"       =>  "41644a",
        "report"        =>  'efb036',
        "setting"       =>  '51829b'
    ];

    public function getGroupMeaning($group){
        if (!array_key_exists($group,$this->group_meaning)){ return ''; }
        return $this->group_meaning[$group];
    }

    public function getGroupColor($group){
        if (!array_key_exists($group,$this->group_color)){ return ''; }
        return $this->group_color[$group];
    }

    public function getPermGroupTitleAttribute()
    {
        return $this->getGroupMeaning($this->permission_group);
    }

    public function getPermGroupColorAttribute()
    {
        return $this->getGroupColor($this->permission_group);
    }

}
