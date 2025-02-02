<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'integer';
    protected $fillable = [
        'id'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
