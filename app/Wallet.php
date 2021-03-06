<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 * @package App
 */
class Wallet extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
