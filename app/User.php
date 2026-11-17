<?php

namespace App;

use App\Models\Branch;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
    |--------------------------------------------------------------------------

        /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
        protected $fillable = [
            'username', 'email', 'password',
        ];

        /**
        * The attributes that should be hidden for arrays.
        *
        * @var array
        */
        protected $hidden = [
            'password', 'remember_token',
        ];
     /*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/ 

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/

    public function branch()
    {
        return $this->hasOne(Branch::class , 'id' , 'branch_id');
    }

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/        
}
