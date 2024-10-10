<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    protected $fillable = ['code', 'description'];

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
