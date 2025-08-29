import { PsObject } from "@templateCore/object/core/PsObject";

export default class SearchHistory extends PsObject<SearchHistory> {

    id: string = '';
    keyword: string = '';
    userId: string = '';
    type: string = '';
    isHomePageSearch: string = '';
    addedDate: string = '';
    addedUserId: string = '';
    updatedDate: string = '';
    updatedUserId: string = '';
    updatedFlag: string = '';
    addedDateStr: string = '';

    init(
        id: string,
        keyword: string,
        userId: string,
        type: string,
        isHomePageSearch: string,
        addedDate: string,
        addedUserId: string,
        updatedDate: string,
        updatedUserId: string,
        updatedFlag: string,
        addedDateStr: string,
    ){
        this.id = id;
        this.keyword = keyword;
        this.userId = userId;
        this.type = type;
        this.isHomePageSearch = isHomePageSearch;
        this.addedDate = addedDate;
        this.addedUserId = addedUserId;
        this.updatedDate = updatedDate;
        this.updatedUserId = updatedUserId;
        this.updatedFlag = updatedFlag;
        this.addedDateStr = addedDateStr;

        return this;
    }

    toMap(object: SearchHistory): any {
        const map = {};

        map['id'] = object.id;
        map['keyword'] = object.keyword;
        map['user_id'] = object.userId;
        map['type'] = object.type;
        map['is_home_page_search'] = object.isHomePageSearch;
        map['added_date'] = object.addedDate;
        map['added_user_id'] = object.addedUserId;
        map['updated_date'] = object.updatedDate;
        map['updated_user_id'] = object.updatedUserId;
        map['updated_flag'] = object.updatedFlag;
        map['added_date_str'] = object.addedDateStr;
        return map;
    }

    toMapList(objectList: SearchHistory[]): any[] {
        const mapList: any[] = [];
        for (let i = 0; i < objectList.length; i++) {
            if (objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new SearchHistory().init(
            obj.id,
            obj.keyword,
            obj.user_id,
            obj.type,
            obj.is_home_page_search,
            obj.added_date,
            obj.added_user_id,
            obj.updated_date,
            obj.updated_user_id,
            obj.updated_flag,
            obj.added_date_str,
        );
    }

    fromMapList(objList: any[]): SearchHistory[] {
        const SearchHistoryList: SearchHistory[] = [];
        for (const obj of objList as Array<SearchHistory>) {
            if (obj != null) {
                SearchHistoryList.push(this.fromMap(obj));
            }
        }

        return SearchHistoryList;
    }
}
