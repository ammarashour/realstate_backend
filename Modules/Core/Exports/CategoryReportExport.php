<?php


namespace Modules\Core\Exports;

use App\Http\Contracts\Category\CategoryServiceInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Core\Entities\Category\Category;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryReportExport implements FromCollection, WithHeadings, WithMapping
{

    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    protected $categoryService;
    public function __construct()
    {
        $this->categoryService = app()->make(CategoryServiceInterface::class);
    }
    public function collection()
    {
        $relation = ['category_touch'];
        $conds = [
            'order_by' => 'category_touch_count',
            'order_type' => 'desc'
        ];
        $categories = $this->categoryService->getAll(relation: $relation, conds: $conds, touchCount: true);

        return $categories;
    }

    public function map($category) : array {
        return [
            $category->name,
            $category->category_touch_count,
            $category->added_date->format('d-M-Y') . ' (' . $category->added_date->diffForHumans() . ')',
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Categories", "Engagement", "Date"];
    }

}
