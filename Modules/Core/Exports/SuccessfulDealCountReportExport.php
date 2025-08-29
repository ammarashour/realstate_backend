<?php


namespace Modules\Core\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Modules\Core\Entities\Item;
use Modules\Core\Constants\Constants;
use Maatwebsite\Excel\Concerns\WithMapping;

use Modules\Core\Entities\Notification\ChatHistory;

use Modules\Core\Entities\Utilities\CustomField;

class SuccessfulDealCountReportExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $itemRelation = [
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation',
            'buyer',
            'seller',
        ];

        return Item::with($itemRelation)
            ->selectRaw($this->getSqlForCustomField())
            ->leftJoin('psx_item_infos', 'psx_item_infos.item_id', '=', 'psx_items.id')
            ->leftJoin('psx_customize_ui_details', 'psx_item_infos.value', '=', 'psx_customize_ui_details.id')
            ->join('psx_user_boughts', 'psx_user_boughts.item_id', '=', 'psx_items.id')
            ->groupBy('psx_items.id', 'psx_user_boughts.buyer_user_id', 'psx_user_boughts.seller_user_id')
            ->latest('psx_items.added_date')
            ->get();
    }

    public function map($item): array
    {
        $offer_amount = '0';
        $conds['item_id'] = $item->id;
        $conds['seller_user_id'] = $item->seller_user_id;
        $conds['buyer_user_id'] = $item->buyer_user_id;
        $chat = ChatHistory::where($conds)->first();
        if ($chat) {
            $offer_amount = $chat->nego_price;
        }

        return [
            $item->buyer ? $item->buyer->name : '',
            $item->seller ? $item->seller->name : '',
            $item->title,
            $item->category ? $item->category->name : '',
            ($item->currency ? $item->currency->currency_symbol : '') . '' . $item->price,
            $offer_amount,
            $item[Constants::itmPurchasedOption . '@@name'],
            $item[Constants::itmItemType . '@@name'],
            $item[Constants::itmDealOption . '@@name'],
            $item->added_date->format('Y/m/d'),
        ];
    }

    public function getSqlForCustomField()
    {
        $sql = "psx_items.*, psx_user_boughts.buyer_user_id, psx_user_boughts.seller_user_id, ";
        $customizeUis = CustomField::where('module_name', 'itm')->latest()->get();

        $customizeuideatil_array = [];

        foreach ($customizeUis as $CustomFieldAttribute) {
            if ($CustomFieldAttribute->ui_type_id == Constants::dropDownUi || $CustomFieldAttribute->ui_type_id == Constants::radioUi || $CustomFieldAttribute->ui_type_id == Constants::multiSelectUi) {
                $customizeuideatil_array[$CustomFieldAttribute->core_keys_id . '@@name'] = $CustomFieldAttribute->core_keys_id;
            }
        }

        foreach (array_unique($customizeuideatil_array) as $key => $customizeuideatil) {
            $sql .= "max(case when psx_item_infos.core_keys_id = '$customizeuideatil' then psx_customize_ui_details.name end) as '$key',";
        }
        foreach ($customizeUis as $key => $customizeUi) {
            if ($key + 1 == count($customizeUis)) {
                $sql .= "max(case when psx_item_infos.core_keys_id = '$customizeUi->core_keys_id' then psx_item_infos.value end) as '$customizeUi->core_keys_id'";
            } else {
                $sql .= "max(case when psx_item_infos.core_keys_id = '$customizeUi->core_keys_id' then psx_item_infos.value end) as '$customizeUi->core_keys_id' ,";
            }
        }
        return $sql;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Buyer Name", "Seller Name", "Item Name", "Categories", "Price", "Offer Amount", "Purchased Option", "Item Type", "Deal Option", "Date"];
    }
}
