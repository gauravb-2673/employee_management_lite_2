<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    protected $table='departments';
    
    protected $fillable =[
        'department_name',
    ];

   
  
  public function employees(): HasMany
  {
      
          return $this->hasMany(Employee::class);
      
      
  }

}
