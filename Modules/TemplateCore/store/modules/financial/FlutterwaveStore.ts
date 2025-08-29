
import { reactive,ref } from 'vue';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useFlutterwaveStoreState = makeSeparatedStore((key: string) =>
defineStore(`flutterWaveStore/${key}`,
 () => {

    let hasExecuted = ref(false);

    async function init() {
        if (!hasExecuted.value) {
            let script = document.createElement('script')
            script.src = "https://checkout.flutterwave.com/v3.js"
            document.body.appendChild(script)
            console.log('coming from mounted in my plugin')
            hasExecuted.value = true
        }
    }


    return {
        init,
    }

}),
);
