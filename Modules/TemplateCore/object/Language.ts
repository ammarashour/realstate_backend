import { PsObject } from "./core/PsObject";

export default class Language extends PsObject<Language>{

    languageCode : string = '';
    countryCode : string = '';
    name : string = '';
   
    init(
        languageCode : string,
        countryCode : string,
        name : string,

    ) {
        this.languageCode = languageCode;
        this.countryCode = countryCode;
        this.name = name;

        return this;

    }

    getPrimaryKey(): string {
        return this.languageCode;
    }

    toMap(object: Language): any {
        const map = {};
        map['language_code'] = object.languageCode;
        map['country_code'] = object.countryCode;
        map['name'] = object.name;
        
        return map;
    }

    toMapList(objectList: Language[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj: any) {
        return new Language().init(


            obj.language_code,
            obj.country_code,
            obj.name,
            
       );
    }   
    fromMapList(objList : any[] ) : Language[] {
        const language : Language[] = [];
        for(const obj of objList as Array<Language>) {
            if(obj != null) {
                language.push(this.fromMap(obj));
            }
        }

        return language;
    }
    

}