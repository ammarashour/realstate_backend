import { PsObject } from "@templateCore/object/core/PsObject";
import Product from "./Product";
import Category from "./Category";
import User from "./User";

export default class AllSearch extends PsObject<AllSearch> {

    item : Product[] = [new Product()];
    category : Category[] = [new Category()];
    user : User[] = [new User()];

    init(
        item : Product[],
        category : Category[],
        user : User[]
    ){
        this.item = item;
        this.category = category;
        this.user = user;

        return this;
    }

    toMap(object: AllSearch): any {
        const map = {};

        map['items'] = new Product().toMapList(object.item);
        map['categories'] = new Category().toMapList(object.category);
        map['users'] = new User().toMapList(object.user);

        return map;
    }

    toMapList(objectList: AllSearch[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new AllSearch().init(
            new Product().fromMapList(obj.items),
            new Category().fromMapList(obj.categories),
            new User().fromMapList(obj.users),
        );
    }

    fromMapList(objList: any[]): AllSearch[] {
        const AllSearchList: AllSearch[] = [];
        for (const obj of objList as Array<AllSearch>) {
            if (obj != null) {
                AllSearchList.push(this.fromMap(obj));
            }
        }

        return AllSearchList;
    }
}
