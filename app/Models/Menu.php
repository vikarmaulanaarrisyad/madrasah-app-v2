<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_parent_id');
    }
}
