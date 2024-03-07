<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Comment extends Model
    {
        protected $table = "comment";
        protected $fillable = ["blog_id", "user", "comment", "approved"];
    }
