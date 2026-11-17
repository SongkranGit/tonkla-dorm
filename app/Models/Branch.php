<?php
/**
 * Created by IntelliJ IDEA.
 * User: Admin
 * Date: 4/8/2019
 * Time: 10:18 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    protected $table = 'branches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name'];

}