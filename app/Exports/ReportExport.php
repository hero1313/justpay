<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
class ReportExport implements FromCollection,WithHeadings
{
    public function collection()
    {
        $type = DB::table('products')->get();
        return $type ;
    }
     public function headings(): array
    {
        return [
            'id',
            'user id',
            'order id',
            'name',
            'price',
            'amount',
            'currency',
            'description',
            'code',
            'image',
            'save index',
            'created at',
            'updated at',
        ];
    }
}