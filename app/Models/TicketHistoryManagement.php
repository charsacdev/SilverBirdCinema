<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistoryManagement extends Model
{
    protected $table = 'ticket_history_management';

     protected $guarded = [];

     public function partner(){
        
        // Assuming 'partner_id' in ticket_history_management links to 'id' in partners_management
        return $this->belongsTo(ParternsManagement::class, 'partner_id', 'id');
    }

    public function ScannerId(){ 
        
        return $this->belongsTo(UserTable::class, 'scanner_id', 'id');
    }
}
