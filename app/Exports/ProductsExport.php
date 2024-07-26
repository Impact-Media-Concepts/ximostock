<?php

namespace App\Exports;

use App\Exports\ProductsExport;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $currentUser;
    protected $productId;
    protected $columns;

    public function __construct(?int $id)
    {
        // Get the current user
        $this->currentUser = Auth::user();

        // Retrieve the table columns dynamically from the 'products' table
        $this->columns = Schema::getColumnListing('products');

        // Example of additional setup based on the ID
        if ($id !== null) {
            $this->productId = $id;
        }
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

        if($this->productId !== null) {
            return Product::query()->where('id', $this->productId)->select($this->columns);
        } else {
            if(isset($this->currentUser->role) && $this->currentUser->role === 'admin') {
                return Product::query()->select($this->columns);
            } else {
                return Product::query()->where('work_space_id', $this->currentUser->work_space_id)->select($this->columns);
            }
        }

        
    }
}
