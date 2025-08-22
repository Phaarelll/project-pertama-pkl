<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // ✅ tambahkan ini
use Usamamuneerchaudhary\Commentify\Traits\Commentable;
class Post extends Model
{
    use Commentable;
}
