<?php

namespace Modules\Core\Imports;

use App\Config\ps_constant;
use App\Http\Contracts\Category\CategoryServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\CoreImage;
use Intervention\Image\Facades\Image;
use Modules\Core\Entities\Category\Subcategory;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Core\Entities\Localization\CategoryLanguageString;

class SubcategoryImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $categoryService;
    public function __construct()
    {
        $this->categoryService = app()->make(CategoryServiceInterface::class);
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $category_id = null;
            $conds = [
                'name' => $row['category_name']
            ];
            $category = $this->categoryService->get(conds: $conds);
            if($category){
                $category_id = $category->id;
            }else{
                $category = CategoryLanguageString::where('value', $row['category_name'])->first();
                if($category){
                    $category_id = $category->category_id;
                }
            }
            if($category_id == null){
                return redirect()->back();
            }
            // save subcategory
            $subcategory = new Subcategory();
            $subcategory->name = $row['name'];
            $subcategory->category_id = $category_id;
            $subcategory->ordering = !empty($row['ordering'])? $row['ordering']: 1;
            $subcategory->status = isset($row['status']) && ($row['status'] == 0 || $row['status'] == 1) ? $row['status'] : 1;

            $subcategory->added_user_id = Auth::user()->id;
            $subcategory->save();

            // for subcategory cover photo
            $photo_name = $row['photo_name'];
            if (!empty($photo_name)){
                $file = public_path() . ps_constant::imgFolderPath . "uploads" . "/" . $photo_name;

                if(File::exists($file)){
                    // save original image to uploads
                    $org_img = Image::make($file);
                    $width = $org_img->width();
                    $height = $org_img->height();
                    $org_img->save(public_path() . ps_constant::imgFolderPath . "uploads" . "/" . $photo_name , 50);

                    // save image to core_images table
                    $cover = new CoreImage();
                    $cover->img_parent_id = $subcategory->id;
                    $cover->img_type = 'subcategory-cover';
                    $cover->img_path = $photo_name;
                    $cover->img_width = $width;
                    $cover->img_height = $height;
                    $cover->added_user_id = Auth::user()->id;
                    $cover->save();
                }
            }

            // for subcategory icon
            $icon_name = $row['icon_name'];
            if(!empty($icon_name)){
                $file = public_path().'/storage/PSX_MPC/uploads/'.$icon_name;
                if(File::exists($file)){
                    // save original image to uploads
                    $org_img = Image::make($file);
                    $width = $org_img->width();
                    $height = $org_img->height();
                    $org_img->save(public_path().'/storage/PSX_MPC/uploads/'.$icon_name,50);

                    // save image to core_images table
                    $icon = new CoreImage();
                    $icon->img_parent_id = $subcategory->id;
                    $icon->img_type = 'subcategory-icon';
                    $icon->img_path = $icon_name;
                    $icon->img_width = $width;
                    $icon->img_height = $height;
                    $icon->added_user_id = Auth::user()->id;
                    $icon->save();
                }
            }
        }
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|unique:psx_subcategories,name',
            'category_name' => 'required',
            "photo_name" => "required",
            "icon_name" => "required",
        ];
    }

    /**
     * custom validation attributes
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            'name' => 'subcategory name',
            'ordering' => 'subcategory ordering',
            'status' => 'subcategory status',
        ];
    }

}
