<?php

namespace App\Entities\Investment;

use App\Entities\Investment\Helpers\InvestmentRelationsHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory,
        InvestmentRelationsHelper,
        SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'guest_identifier',
        'amount'
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
