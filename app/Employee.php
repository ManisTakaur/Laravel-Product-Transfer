<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_employee';
    protected $primaryKey ='employeeId';

    protected $fillable = [
        'employeeName','employeePassword'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
}
