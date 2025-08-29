import { PsObject } from "./core/PsObject";

export default class VendorBranches extends PsObject<VendorBranches> {

    id : string = '';
    vendorId : string = '';
    name : string = '';
    email : string = '';
    phone : string = '';
    address : string = '';
    description : string = '';
    addedDateStr : string = '';

    init(
        id : string,
        vendorId : string,
        name : string,
        email : string,
        phone : string,
        address : string,
        description : string,
        addedDateStr : string,

    ) {
        this.id = id;
        this.vendorId = vendorId;
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.address = address;
        this.description = description;
        this.addedDateStr = addedDateStr;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: VendorBranches): any {
        const map = {};
        map['id'] = object.id;
        map['vendor_id'] = object.vendorId;
        map['name'] = object.name;
        map['email'] = object.email;
        map['phone'] = object.phone;
        map['address'] = object.address;
        map['description'] = object.description;
        map['added_date_str'] = object.addedDateStr;

        return map;
    }

    toMapList(objectList: VendorBranches[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new VendorBranches().init(

            obj.id,
            obj.vendor_id,
            obj.name,
            obj.email,
            obj.phone,
            obj.address,
            obj.description,
            obj.added_date_str,
        );
    }

    fromMapList(objList: any[]): VendorBranches[] {
        const VendorBranches: VendorBranches[] = [];
        for (const obj of objList as Array<VendorBranches>) {
            if (obj != null) {
                VendorBranches.push(this.fromMap(obj));
            }
        }

        return VendorBranches;
    }
}
