<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bContractItem extends Model
{
    protected $fillable = ['b2b_contract_id', 'item_id', 'contract_price'];

    public function contract()
    {
        return $this->belongsTo(B2bContract::class, 'b2b_contract_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
