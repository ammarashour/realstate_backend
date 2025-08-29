import { PsObject } from "./core/PsObject";

export default class ItemCurrency extends PsObject<ItemCurrency>{


    id : string = '';
    currencyShortForm : string = '';
    status : string = '';
    addedDate : string = '';
    currencySymbol : string = '';

    getPrimaryKey(): string {
        return this.id;
    }


    init(
        id: string,
        currencyShortForm: string,
        status: string,
        addedDate: string,
        currencySymbol: string,

    ) {
        this.id = id;
        this.currencyShortForm = currencyShortForm;
        this.status = status;
        this.addedDate = addedDate;
        this.currencySymbol = currencySymbol;

        return this;

    }

    fromMap(obj : any) {
        return new ItemCurrency().init(
         obj.id,
         obj.currency_short_form,
         obj.status,
         obj.added_date,
         obj.currency_symbol,
        
        );
    }

    fromMapList(objList : any[] ) : ItemCurrency[] {
        const itemCurrency : ItemCurrency[] = [];
        for(const obj in objList) {
            if(obj != null) {
                itemCurrency.push(this.fromMap(obj));
            }
        }

        return itemCurrency;
    }

    toMap(object: ItemCurrency) : any {
        const map = {};

        map['id'] = object.id;
        map['currency_short_form'] = object.currencyShortForm;
        map['status'] = object.status;
        map['added_date'] = object.addedDate;
        map['currency_symbol'] = object.currencySymbol;
        
        return map;
    }

    toMapList(objectList: ItemCurrency[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }
}
