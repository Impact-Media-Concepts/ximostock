<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'sku', 
            'ean',
            'title', 
            'short_description', 
            'long_description', 
            'price', 
            'discount', 
            'backorders', 
            'communicate_stock', 
            'created_at', 
            'updated_at'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_ACCOUNTING_EUR,
            'G' => NumberFormat::FORMAT_ACCOUNTING_EUR,
            'J' => NumberFormat::FORMAT_DATE_DATETIME,
            'K' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function map($product): array
    {
        return [
            $product->sku,
            $product->ean,
            $product->title,
            $product->short_description,
            $product->long_description,
            $product->price,
            $product->discount,
            $product->backorders ? 'true' : 'false',
            $product->communicate_stock  ? 'true' : 'false',
            Date::dateTimeToExcel($product->created_at),
            Date::dateTimeToExcel($product->created_at)            
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Product::query()->select('sku', 'ean','title', 'short_description', 'long_description', 'price', 'discount', 'backorders', 'communicate_stock', 'created_at', 'created_at');
    }

    
}
