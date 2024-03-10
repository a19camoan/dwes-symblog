<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Blog extends Model
    {
        protected $table = "blog";
        protected $fillable = ["title", "author", "blog", "image", "tags"];
        public $timestamps = false;

        public function comments()
        {
            return $this->hasMany(Comment::class);
        }
    }
