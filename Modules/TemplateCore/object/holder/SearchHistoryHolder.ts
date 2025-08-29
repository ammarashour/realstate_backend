export default class SearchHistoryHolder {

    userId: string = '';
    type: string = '';


    SearchHistoryHolder() {
        this.userId = '';
        this.type = '';
    }

    getAllSearchHistoryList() : SearchHistoryHolder {
        this.userId = '';
        this.type = 'all';

        return this;
    }

    getItemSearchHistoryList() : SearchHistoryHolder {
        this.userId = '';
        this.type = 'item';

        return this;
    }

    getCategorySearchHistoryList() : SearchHistoryHolder {
        this.userId = '';
        this.type = 'category';

        return this;
    }

    getUserSearchHistoryList() : SearchHistoryHolder {
        this.userId = '';
        this.type = 'user';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['user_id'] = this.userId;
        map['type'] = this.type;

        return map;
    }
}
