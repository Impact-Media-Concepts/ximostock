<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
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

    protected $columns;

    public function __construct()
    {
        // Retrieve the table columns dynamically
        $this->columns = Schema::getColumnListing('products');
    }

    public function headings(): array
    {
        // Use the dynamically retrieved columns as headings
        return $this->columns;
    }

    public function columnFormats(): array
    {
        // Manually specify the formats for specific columns as needed
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
        // Map the product attributes dynamically
        $mappedProduct = [];
        foreach ($this->columns as $column) {
            if ($column === 'created_at' || $column === 'updated_at') {
                $mappedProduct[] = Date::dateTimeToExcel($product->$column);
            } elseif ($column === 'backorders' || $column === 'communicate_stock') {
                $mappedProduct[] = $product->$column ? 'true' : 'false';
            } else {
                $mappedProduct[] = $product->$column;
            }
        }
        return $mappedProduct;
    }

    /**
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function query()
    {
        // Use the dynamically retrieved columns in the query
        return Product::query()->select($this->columns);
    }
}
