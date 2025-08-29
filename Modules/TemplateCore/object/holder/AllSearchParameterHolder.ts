export default class AllSearchParameterHolder {

    keyword: string = '';
    type: string = '';


    AllSearchParameterHolder() {
        this.keyword = '';
        this.type = '';
    }

    toMap(): {} {
        const map = {};
        map['keyword'] = this.keyword;
        map['type'] = this.type;

        return map;
    }
}
