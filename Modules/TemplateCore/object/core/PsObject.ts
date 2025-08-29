export abstract class PsObject<T> {
    
    abstract getPrimaryKey() : string;
  
    abstract fromMap(dynamicData: any) : T ;
  
    abstract toMap(object : T): any;
  
    abstract fromMapList(dynamicDataList: any[]) : T[] ;
  
    abstract toMapList(objectList : T[]) : any;
  }