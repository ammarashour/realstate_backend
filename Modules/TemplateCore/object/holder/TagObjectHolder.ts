export default class TagObjectHolder {

    fieldName: string = '';
    tagId: string = '';
    tagName: string = '';

    TagObjectHolder() {
        this.fieldName = '';
        this.tagId = '';
        this.tagName = '';
    }

    toMap(): {} {
        const map = {};
        map['field_name'] = this.fieldName;
        map['tag_id'] = this.tagId;
        map['tag_name'] = this.tagName;

        return map;
    }
}