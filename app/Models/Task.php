<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *   required={
 *     "id",
 *     "assigneduser_id",
 *     "title",
 *     "description",
 *     "due",
 *     "completed",
 *   },
 * 	@OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID da tarefa",
 *         example="2"
 *     ),
 * 	@OA\Property(
 *         property="assigneduser_id",
 *         type="integer",
 *         description="Id do responsável pela tarefa",
 *         example="1"
 *     ),
 * 	@OA\Property(
 *         property="title",
 *         type="string",
 *         description="Título da tarefa",
 *         example="New task"
 *     ),
 * 	@OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descrição da tarefa",
 *         example="loren impsum"
 *     ),
 * 	@OA\Property(
 *         property="due",
 *         type="string",
 *         description="Data final da entrega da tarefa",
 *         example="2024-04-20 00:00:00"
 *     ),
 * 	@OA\Property(
 *         property="completed",
 *         type="boolean",
 *         description="Se a tarefa foi finalizada ou não",
 *         example="0"
 *     )
 * )
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

    public function scopeFilterDate($search)
    {        
        return $this
            ->where('created_at','like', '%'. $search .'%')->get();
    }

    public function between($req)
    {
        return Task::whereBetween('created_at',[Carbon::parse($req->from)->format('Y-m-d 00:00:00'),Carbon::parse($req->to)->format('Y-m-d 23:59:59')])->get();
    }

}