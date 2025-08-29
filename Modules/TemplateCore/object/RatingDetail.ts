
import { PsObject } from "@templateCore/object/core/PsObject";


export default class RatingDetail extends PsObject<RatingDetail> {

        fiveStarCount : string = '';
        fiveStarPercent : string = '';
        fourStarCount : string = '';
        fourStarPercent : string = '';
        threeStarCount : string = '';
        threeStarPercent : string = '';
        twoStarCount : string = '';
        twoStarPercent : string = '';
        oneStarCount : string = '';
        oneStarPercent : string = '';
        totalRatingCount : string = '';
        totalRatingValue : string = '';
     

    init(

        fiveStarCount : string,
        fiveStarPercent : string,
        fourStarCount : string,
        fourStarPercent : string,
        threeStarCount : string,
        threeStarPercent : string,
        twoStarCount : string,
        twoStarPercent : string,
        oneStarCount : string,
        oneStarPercent : string,
        totalRatingCount : string,
        totalRatingValue : string,
        
        ) {
            this.fiveStarCount = fiveStarCount;
            this.fiveStarPercent = fiveStarPercent;
            this.fourStarCount = fourStarCount;
            this.fourStarPercent = fourStarPercent;
            this.threeStarCount = threeStarCount;
            this.threeStarPercent = threeStarPercent;
            this.twoStarCount = twoStarCount;
            this.twoStarPercent = twoStarPercent;
            this.oneStarCount = oneStarCount;
            this.oneStarPercent = oneStarPercent;
            this.totalRatingCount = totalRatingCount;
            this.totalRatingValue = totalRatingValue;

        return this;

    }
    
    getPrimaryKey(): string {
        return this.fiveStarCount;
    }

    toMap(object: RatingDetail) : any {
        const map = {};

        map['five_star_count'] = object.fiveStarCount;
        map['five_star_percent'] = object.fiveStarPercent;
        map['four_star_count'] = object.fourStarCount;
        map['four_star_percent'] = object.fourStarPercent;
        map['three_star_count'] = object.threeStarCount;
        map['three_star_percent'] = object.threeStarPercent;
        map['two_star_count'] = object.twoStarCount;
        map['two_star_percent'] = object.twoStarPercent;
        map['one_star_count'] = object.oneStarCount;
        map['one_star_percent'] = object.oneStarPercent;
        map['total_rating_count'] = object.totalRatingCount;
        map['total_rating_value'] = object.totalRatingValue;
    

        return map;
    }

    toMapList(objectList: RatingDetail[]) : any[] {
        const mapList : any[] = [];
        for(let i = 0; i < objectList.length; i++) {
            if(objectList[i] != null) {
                mapList.push(this.toMap(objectList[i]));
            }
        }

        return mapList;
    }

    fromMap(obj : any) {
        return new RatingDetail().init(
        obj.five_star_count,
        obj.five_star_percent,
        obj.four_star_count,
        obj.four_star_percent,
        obj.three_star_count,
        obj.three_star_percent,
        obj.two_star_count,
        obj.two_star_percent,
        obj.one_star_count,
        obj.one_star_percent,
        obj.total_rating_count,
        obj.total_rating_value,
        );        
    }

    fromMapList(objList : any[] ) : RatingDetail[] {
        const ratingDetail : RatingDetail[] = [];
        for(const obj in objList) {
            if(obj != null) {
                ratingDetail.push(this.fromMap(obj));
            }
        }

        return ratingDetail;
    }
}
