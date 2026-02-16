<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'discussion_id',
        'user_id',
        'content',
    ];
    // reply dimiliki oleh satu diskusi
    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    // reply dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
