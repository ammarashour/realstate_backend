<?php

namespace Modules\Core\Http\Requests\Item;

use App\Http\Contracts\Configuration\SettingServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateItemRequest extends FormRequest
{
    protected $coreFieldFilterSettingService;
    public function __construct(CoreFieldFilterSettingService $coreFieldFilterSettingService, protected SettingServiceInterface $settingService)
    {
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
    }

    public function rules()
    {
        // Validate the custom fields
        $errors = validateForCustomField(Constants::item, $this->product_relation, $this->category_id);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::item);

        $default_images = CoreImage::where('img_type', 'item')->where('img_parent_id', $this->id)->count();

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $selcted_array = $this->settingService->get(env: Constants::SYSTEM_CONFIG);
        $jsonSetting = json_decode($selcted_array->setting, true);

        $validationRules = array(
            array(
                'fieldName' => 'status',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'extra_caption',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'item_image',
                'rules' => $default_images == 0 ? 'required' : 'nullable',
            ),
            array(
                'fieldName' => 'title',
                'rules' => 'required|min:3',
            ),
            array(
                'fieldName' => 'description',
                'rules' => 'required|min:10',
            ),
            array(
                'fieldName' => 'category_id',
                'rules' => 'required|exists:psx_categories,id',
            ),
            array(
                'fieldName' => 'subcategory_id',
                'rules' => 'required|exists:psx_subcategories,id',
            ),
            array(
                'fieldName' => 'location_city_id',
                'rules' => 'required|exists:psx_location_cities,id',
            ),
            array(
                'fieldName' => 'location_township_id',
                'rules' => 'required|exists:psx_location_townships,id',
            ),
            array(
                'fieldName' => 'currency_id',
                'rules' => $jsonSetting['selected_price_type']['id'] == "NORMAL_PRICE" ? 'required|exists:psx_currencies,id' : 'nullable',
            ),
            array(
                'fieldName' => 'original_price',
                'rules' => 'required|max:11',
            ),
            array(
                'fieldName' => 'price',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'percent',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'lat',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'lng',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'shop_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'search_tag',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'ordering',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'is_discount',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'phone',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'video_icon',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'video',
                'rules' => 'nullable|sometimes|mimetypes:video/mp4',
            ),
            array(
                'fieldName' => 'images',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'img_order',
                'rules' => 'nullable',
            ),

        );

        $validationArr = handleValidation($errors, $coreFields, $validationRules);

        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::item, $this->product_relation);

        $coreFieldAttributeArr = [
            'original_price.max' => "The original price must not be greater than 6 digits.",
        ];
        $attributeArr = array_merge($coreFieldAttributeArr, $customFieldAttributeArr);

        return $attributeArr;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


}
