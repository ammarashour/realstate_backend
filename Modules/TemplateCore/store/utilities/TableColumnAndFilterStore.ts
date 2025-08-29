import { reactive,ref } from 'vue';
import { trans } from 'laravel-vue-i18n';
import CategoryListParameterHolder from '@templateCore/object/holder/CategoryListParameterHolder';
import PsApiService from '@templateCore/api/PsApiService';
import Category from '@templateCore/object/Category';
import PsResource from '@templateCore/api/common/PsResource';
import { defineStore } from 'pinia'
import { store } from '@templateCore/store/modules/core/PsStore';
import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

export const useTableColumnAndFilterStoreState = makeSeparatedStore((key: string) =>
    defineStore(`tableColumnAndFilterStore/${key}`,
    () => {

        function tableColumnsMapping(columns){ 
            
            return columns.map(column => {
                return {
                    action: column.action,
                    field: column.field,
                    label: trans(column.label),
                    sort: column.sort,
                    type: column.type
                };
            });
        }

        function tableFiltersMapping(filters){ 
            
            return filters.map(filter => {
                return {
                    hidden: filter.hidden,
                    id: filter.id,
                    key: trans(filter.key),
                    key_id: filter.key_id,
                    label: trans(filter.label),
                    module_name: filter.module_name
                };
            });
        }

        return{
            tableColumnsMapping,
            tableFiltersMapping
        }

    }),
);


// Need to be used outside the setup
export function useTableColumnAndFilterStoreStateWithout() {
    return useTableColumnAndFilterStoreState(store);
  }


