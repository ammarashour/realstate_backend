import { PsObject } from "./core/PsObject";

export default class VendorApplication extends PsObject<VendorApplication> {

    id : string = '';
    vendorId : string = '';
    userId : string = '';
    document : string = '';
    coverLetter : string = '';
    addedDateStr : string = '';


    init(
        id : string,
        vendorId : string,
        userId : string,
        document : string,
        coverLetter : string,
        addedDateStr : string

    ) {
        this.id = id;
        this.vendorId = vendorId;
        this.userId = userId;
        this.document = document;
        this.coverLetter = coverLetter;
        this.addedDateStr = addedDateStr

        return this;

    }

    getPrimaryKey(): string {
        return 'vendor';
    }

    toMap(object: VendorApplication): any {
        const map = {};
        map['id'] = object.id;
        map['vendor_id'] = object.vendorId;
        map['user_id'] = object.userId;
        map['document'] = object.document;
        map['cover_letter'] = object.coverLetter;
        map['added_date_str'] = object.addedDateStr;
        return map;
    }

    toMapList(objectList: VendorApplication[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new VendorApplication().init(

            obj.id,
            obj.vendor_id,
            obj.user_id,
            obj.document,
            obj.cover_letter,
            obj.added_date_str
        );
    }

    fromMapList(objList: any[]): VendorApplication[] {
        const vendorApplicationList: VendorApplication[] = [];
        for (const obj of objList as Array<VendorApplication>) {
            if (obj != null) {
                vendorApplicationList.push(this.fromMap(obj));
            }
        }

        return vendorApplicationList;
    }
}
