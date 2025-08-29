export default class DeleteSearchHistoryHolder {

    ids: any[] = [];


    DeleteSearchHistoryHolder() {
        this.ids = [];
    }

    toMap(): {} {
        const map = {};
        map['ids'] = this.ids;

        return map;
    }
}
