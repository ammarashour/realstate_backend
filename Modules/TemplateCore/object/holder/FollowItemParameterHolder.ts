
export default class FollowItemParameterHolder {

    itemLocationId: string = '';
    itemLocationTownship: string = '';

    FollowItemParameterHolder() {
        this.itemLocationId = '';
        this.itemLocationTownship = '';
    }


    toMap(): {} {
        const map = {};
        map['item_location_id'] = this.itemLocationId;
        map['item_location_township_id'] = this.itemLocationTownship;
        return map;
    }
}