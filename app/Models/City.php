<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

/**
 * @property int $id
 * @property int $prefecture_id
 * @property string $code
 * @property string $name
 * @property bool $big_city_flag
 * @property int $order_num
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class City extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['prefecture_id', 'code', 'name', 'big_city_flag', 'order_num', 'created_at', 'updated_at', 'deleted_at'];
}
