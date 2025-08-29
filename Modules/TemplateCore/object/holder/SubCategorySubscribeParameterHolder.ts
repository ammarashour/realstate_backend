type SubCategoryScribeParameterHolderInterface = {
    userId: string;
    catId: string;
    subCatIds: string[];
}

export default class SubCategorySubscribeParameterHolder implements SubCategoryScribeParameterHolderInterface{

    userId: string = '';
    catId: string = '';
    subCatIds: string[] = [];
    
    SubCategorySubscribeParameterHolder() {
        this.userId = '';
        this.catId = '';
        this.subCatIds = [];

        return this;
    }

    toMap(): {} {
        const map = {
            "user_id": this.userId,
            "cat_id": this.catId,
            "sub_cat_ids": this.subCatIds
        }

        return map;
    }
}