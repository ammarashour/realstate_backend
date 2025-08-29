type ImageParameterHolderInterface = {
    imgId: string;
    itemId: string;
    file: string;
    ordering: string;
    imgType: string;
}

export default class ImageParameterHolder implements ImageParameterHolderInterface{

    imgId: string = '';
    itemId: string = '';
    file: string = '';
    ordering: string = '';
    imgType: string = '';
    

    ImageParameterHolder() {
        this.imgId = '';
        this.itemId = '';
        this.file = '';
        this.ordering = '';
        this.imgType = '';

        return this;
    }

    toMap(): {} {
        const map = {};
        map['img_id'] = this.imgId;
        map['item_id'] = this.itemId;
        map['file'] = this.file;
        map['ordering'] = this.ordering;
        map['img_type'] = this.imgType;

        return map;
    }
}