import { PsObject } from "./core/PsObject";
import VendorApplication from "./VendorApplication";
import DefaultPhoto from "./DefaultPhoto";
import ProductRelation from "./ProductRelation";
// import VendorBranches from "./VendorBranches";

export default class Vendor extends PsObject<Vendor> {

    id : string = '';
    ownerUserId : string = '';
    status : string = '';
    name : string = '';
    phone : string = '';
    email : string = '';
    address : string = '';
    description : string = '';
    website : string = '';
    facebook : string = '';
    instagram : string = '';
    productCount : string = '';
    currencyId: string = '';
    addedDate : string = '';
    expiredDate : string = '';
    expireStatus : string = '';
    logo : DefaultPhoto = new DefaultPhoto();
    banner1 : DefaultPhoto = new DefaultPhoto();
    banner2 : DefaultPhoto = new DefaultPhoto();
    vendorApplication : VendorApplication = new VendorApplication();
    addedDateStr : string = '';
    updatedFlag : string = '';
    vendorRelation : ProductRelation[] = [new ProductRelation()];
    // vendorBranches : VendorBranches[] = [new VendorBranches()];

    init(
        id : string,
        ownerUserId : string,
        status : string,
        name : string,
        phone : string,
        email : string,
        address : string,
        description : string,
        website : string,
        facebook : string,
        instagram : string,
        productCount : string,
        currencyId : string,
        addedDate : string,
        expiredDate : string,
        expireStatus : string,
        logo : DefaultPhoto,
        banner1 : DefaultPhoto,
        banner2 : DefaultPhoto,
        vendorApplication : VendorApplication,
        addedDateStr : string,
        updatedFlag : string,
        vendorRelation : ProductRelation[],
        // vendorBranches : VendorBranches[],

    ) {
        this.id = id;
        this.ownerUserId = ownerUserId;
        this.status = status;
        this.name = name;
        this.phone = phone;
        this.email = email;
        this.address = address;
        this.description = description;
        this.website = website;
        this.facebook = facebook;
        this.instagram = instagram;
        this.productCount = productCount;
        this.currencyId = currencyId;
        this.addedDate = addedDate;
        this.expiredDate = expiredDate;
        this.expireStatus = expireStatus;
        this.logo = logo;
        this.banner1 = banner1;
        this.banner2 = banner2;
        this.vendorApplication = vendorApplication;
        this.addedDateStr = addedDateStr;
        this.updatedFlag = updatedFlag;
        this.vendorRelation = vendorRelation;
        // this.vendorBranches = vendorBranches;

        return this;

    }

    getPrimaryKey(): string {
        return this.id;
    }

    toMap(object: Vendor): any {
        const map = {};
        map['id'] = object.id;
        map['owner_user_id'] = object.ownerUserId;
        map['status'] = object.status;
        map['name'] = object.name;
        map['phone'] = object.phone;
        map['email'] = object.email;
        map['address'] = object.address;
        map['description'] = object.description;
        map['website'] = object.website;
        map['facebook'] = object.facebook;
        map['instagram'] = object.instagram;
        map['product_count'] = object.productCount;
        map['currency_id'] = object.currencyId;
        map['added_date'] = object.addedDate;
        map['expired_date'] = object.expiredDate;
        map['expire_status'] = object.expireStatus;
        map['logo'] = new DefaultPhoto().toMap(object.logo);
        map['banner_1'] = new DefaultPhoto().toMap(object.banner1);
        map['banner_2'] = new DefaultPhoto().toMap(object.banner2);
        map['vendor_application'] = new VendorApplication().toMap(object.vendorApplication);
        map['added_date_str'] = object.addedDateStr;
        map['updated_flag'] = object.updatedFlag;
        map['vendorRelation'] = new ProductRelation().toMapList(object.vendorRelation);
        // map['vendorBranches'] = new VendorBranches().toMapList(object.vendorBranches);

        return map;
    }

    toMapList(objectList: Vendor[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Vendor().init(

            obj.id,
            obj.owner_user_id,
            obj.status,
            obj.name,
            obj.phone,
            obj.email,
            obj.address,
            obj.description,
            obj.website,
            obj.facebook,
            obj.instagram,
            obj.product_count,
            obj.currency_id,
            obj.added_date,
            obj.expired_date,
            obj.expire_status,
            new DefaultPhoto().fromMap(obj.logo),
            new DefaultPhoto().fromMap(obj.banner_1),
            new DefaultPhoto().fromMap(obj.banner_2),
            new VendorApplication().fromMap(obj.vendor_application),
            obj.added_date_str,
            obj.updated_flag,
            new ProductRelation().fromMapList(obj.vendorRelation),
            // new VendorBranches().fromMapList(obj.vendorBranches),
        );
    }

    fromMapList(objList: any[]): Vendor[] {
        const vendorList: Vendor[] = [];
        for (const obj of objList as Array<Vendor>) {
            if (obj != null) {
                vendorList.push(this.fromMap(obj));
            }
        }

        return vendorList;
    }
}
