<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

/**
 * @property integer $id
 * @property integer $event_id
 * @property boolean $target_gender
 * @property boolean $target_age_type
 * @property int $target_age_from
 * @property int $target_age_to
 * @property string $other_conditions
 * @property boolean $limit_number_of_participants_flag
 * @property int $limit_number_of_participants
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class EventCondition extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    use HasFactory, SoftDeletes, Sortable;

    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['event_id', 'target_gender', 'target_age_type', 'target_age_from', 'target_age_to', 'other_conditions', 'limit_number_of_participants_flag', 'limit_number_of_participants', 'created_at', 'updated_at', 'deleted_at'];
}