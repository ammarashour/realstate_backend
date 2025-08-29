import { PsObject } from "./core/PsObject";

export default class PsItemUploadConfig extends PsObject<PsItemUploadConfig>{

    address : string = '';
    brand : string = '';
    lat : string = '';
    lng : string = '';
    business_mode : string = '';
    subCatId : string = '';
    itemTypeId : string = '';
    itemPriceTypeId : string = '';
    conditionOfItemId : string = '';
    dealOptoionId : string = '';
    dealOptionRemark : string = '';
    highlightInfo : string = '';
    discountRate : string = '';
   
    init(

        address : string,
        brand : string,
        lat : string,
        lng : string,
        business_mode : string,
        subCatId : string,
        itemTypeId : string,
        itemPriceTypeId : string,
        conditionOfItemId : string,
        dealOptoionId : string,
        dealOptionRemark : string,
        highlightInfo : string,
        discountRate : string

       

    ) {

        this.address = address;
        this.brand = brand;
        this.lat = lat;
        this.lng = lng;
        this.business_mode = business_mode;
        this.subCatId = subCatId;
        this.itemTypeId = itemTypeId;
        this.itemPriceTypeId = itemPriceTypeId;
        this.conditionOfItemId = conditionOfItemId;
        this.dealOptoionId  = dealOptoionId;
        this.dealOptionRemark  = dealOptionRemark;
        this.highlightInfo = highlightInfo;
        this.discountRate = discountRate;

        return this;

    }

    getPrimaryKey(): string {
        return this.address;
    }

    toMap(object: PsItemUploadConfig): any {
        const map = {};

        map['address'] = object.address;
        map['brand'] = object.brand;
        map['lat'] = object.lat;
        map['lng'] = object.lng;
        map['business_mode'] = object.business_mode;
        map['sub_cat_id'] = object.subCatId;
        map['item_type_id'] = object.itemTypeId;
        map['item_price_type_id'] = object.itemPriceTypeId;
        map['condition_of_item_id'] = object.conditionOfItemId;
        map['deal_option_id'] = object.dealOptoionId;
        map['deal_option_remark'] = object.dealOptionRemark;
        map['highlight_info'] = object.highlightInfo;
        map['discount_rate_by_percentage'] = object.discountRate;
        
        return map;
    }

    toMapList(objectList: PsItemUploadConfig[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new PsItemUploadConfig().init(



            obj.address,
            obj.brand,
            obj.lat,
            obj.lng,
            obj.business_mode,
            obj.sub_cat_id,
            obj.item_type_id,
            obj.item_price_type_id,
            obj.condition_of_item_id,
            obj.deal_option_id,
            obj.deal_option_remark,
            obj.highlight_info,
            obj.discount_rate_by_percentage

       );
    }   
    fromMapList(objList : any[] ) : PsItemUploadConfig[] {
        const psItemConfigList : PsItemUploadConfig[] = [];
        for(const obj in objList) {
            if(obj != null) {
                psItemConfigList.push(this.fromMap(obj));
            }
        }

        return psItemConfigList;
    }
    

}