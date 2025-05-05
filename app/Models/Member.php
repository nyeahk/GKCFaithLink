<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function profile()
    {
        return view('member.profile');
    }
}
