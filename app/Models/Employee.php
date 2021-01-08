<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name 名字
 * @property string line_id Line 的 ID
 * @property timestamp duty_date 值日生日期
 * @property integer order 排序
 */
class Employee extends Model
{
    use HasFactory;
}
