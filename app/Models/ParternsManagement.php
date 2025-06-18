<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParternsManagement extends Model
{
    //

     protected $table = 'parterns_management';

     protected $guarded = [];

      public function tickets(){
        
        return $this->hasMany(TicketHistoryManagement::class, 'partner_id', 'id');
    }
}
