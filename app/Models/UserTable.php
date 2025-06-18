<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserTable extends Authenticatable
{
    use HasFactory; // Optional

    protected $table = 'user_tables';

    protected $guarded = [];

 
    public function scopeSalesAgents($query)
    {
        return $query->where('type', 'agent');
    }

   
    public function scopeAdmins($query)
    {
        // Assuming 'sub_admin' is the type for admins based on your description
        return $query->where('type', 'sub_admin');
    }

    // Optional: Helper methods for roles
    public function isAdmin()
    {
        return $this->type === 'super';
    }

    public function isManager()
    {
        return $this->type === 'sub_admin';
    }

     public function isAgent()
    {
        return $this->type === 'agent';
    }
}
