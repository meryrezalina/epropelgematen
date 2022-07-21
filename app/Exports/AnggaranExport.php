<?php

namespace App\Exports;

use App\Models\Anggaran;
use App\Exports\DataExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AnggaranExport implements WithMultipleSheets
{
    protected $anggaranID;
   

    public function __construct($anggaranID)
    {
        $this->anggaranID = $anggaranID;
        
    }

   public function sheets() : array
   {
       $sheets = new DataExport($this->anggaranID);
        
       return $sheets;
   }
}
