<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @OA\Schema()
 */

class Task extends Model
{
    use HasFactory, TaskUserManager;

    protected $guarded = [];

    public function formatDate()
    {
        return $this->getDates() ;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeFilter($search)
    {        
        return $this
            ->where('due','like', '%'. $search .'%')
            ->orWhere('created_at','like', '%'. $search .'%')
            ->orWhere('title','like', '%'. $search .'%')
            ->orWhere('description','like', '%'. $search .'%')->get();
    }

    public function between($req)
    {
        return Task::whereBetween('created_at',[Carbon::parse($req->from)->format('Y-m-d 00:00:00'),Carbon::parse($req->to)->format('Y-m-d 23:59:59')])->get();
    }

}