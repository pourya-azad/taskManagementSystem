<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     description="A task in the task management system",
 *     required={"title", "priority", "status"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Task ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the task"
 *     ),
 *     @OA\Property(
 *         property="desc",
 *         type="string",
 *         description="Description of the task"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="Date of the task"
 *     ),
 *     @OA\Property(
 *         property="priority",
 *         type="string",
 *         description="Priority level of the task"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"toDo", "inProgress", "done"},
 *         description="Current status of the task"
 *     )
 * )
 */


class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'priority',
        'status',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
