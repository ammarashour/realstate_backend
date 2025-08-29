import format from 'number-format.js';
import { ref } from "vue";

export default class PsUtils {

    static formatDate(date: Date): string {

        const day = ("0" + date.getDate()).slice(-2);
        const month = ("0" + (date.getMonth() + 1)).slice(-2);
        const year = date.getFullYear();
        const hour = ("0" + date.getHours()).slice(-2);
        const mins = ("0" + date.getMinutes()).slice(-2);
        const sec = ("0" + date.getSeconds()).slice(-2);

        return year + "-" + month + "-" + day + " " + hour + ":" + mins + ":" + sec;

    }

    static toHHMMSS = (seconds) => {
        seconds = Number(seconds);
        const d = Math.floor(seconds / (3600*24));
        const h = Math.floor(seconds % (3600*24) / 3600);
        const m = Math.floor(seconds % 3600 / 60);
        const s = Math.floor(seconds % 60);

        const dDisplay = d > 0 ? d + ("d : ") : "00d : ";
        const hDisplay = h > 0 ? h + ("h : ") : "00h : ";
        const mDisplay = m > 0 ? m + ("m : ") : "00m : ";
        const sDisplay = s > 0 ? s + ("s ") : "00s ";
        return dDisplay + hDisplay + mDisplay + sDisplay;
    }

    static secondToDuration = (seconds) => {
        seconds = Number(seconds);
        const d = Math.floor(seconds / (3600*24));
        const h = Math.floor(seconds % (3600*24) / 3600);
        const m = Math.floor(seconds % 3600 / 60);
        const s = Math.floor(seconds % 60);

        const dDisplay = d > 0 ? d + (" day ") : "";
        const hDisplay = h > 0 ? h + (" hr ") : "";
        const mDisplay = m > 0 ? m + (" min ") : "";
        const sDisplay = s > 0 ? s + (" seconds ") : "";
        return dDisplay + hDisplay + mDisplay + sDisplay;
    }

    static timeStampToDateStringWithPeriod(UNIX_timestamp) {
        if (UNIX_timestamp == '' || UNIX_timestamp == null) {
            return "-";
        }

        let date;

        const tmp = UNIX_timestamp + '';

        if (tmp.length <= 10) {
            date = new Date(UNIX_timestamp * 1000);
        } else {
            date = new Date(UNIX_timestamp);
        }

        const months = [
            'Jan', 'Feb', 'Mar', 'Apr',
            'May', 'Jun', 'Jul', 'Aug',
            'Sep', 'Oct', 'Nov', 'Dec'
          ];
        
          const monthName = months[date.getMonth()];
          const day = date.getDate().toString().padStart(2, '0');
          const year = date.getFullYear();
        
          const hours = ((date.getHours() + 11) % 12 + 1).toString().padStart(2, '0');
          const minutes = date.getMinutes().toString().padStart(2, '0');
          const period = date.getHours() < 12 ? 'AM' : 'PM';
        
          const formattedDateTime = `${monthName} ${day}, ${year} / ${hours}:${minutes} ${period}`;
        
          return formattedDateTime;
    }

    static timeStampToDateString(UNIX_timestamp){

        if(UNIX_timestamp == '' || UNIX_timestamp == null) {
            return "-";
        }

        let a;

        const tmp = UNIX_timestamp + '';

        if(tmp.length <= 10) {
            a = new Date(UNIX_timestamp * 1000);
        }else{
            a = new Date(UNIX_timestamp);
        }
        const months = ['1','2','3','4','5','6','7','8','9','10','11','12'];
        const year = a.getFullYear();
        const month = months[a.getMonth()];
        const date = a.getDate();
        const hour = a.getHours();
        const min = a.getMinutes();
        const sec = a.getSeconds();

        //const h = hour > 12 ? a.getHours() - 12 : a.getHours() ;
        const h = hour < 10 ? ( "0" + a.getHours() ) : a.getHours();
        const m = min < 10 ? ( "0" + a.getMinutes() ) : a.getMinutes();
        const s = sec < 10 ? ( "0" + a.getSeconds() ) : a.getSeconds();
        //const ampm = hour > 12 ? "PM" : "AM";
        const time = month + '-' + date + '-' + year + ' ' + h + ':' + m;
        return time;
      }

    static getTimeStampDividedByOneThousand(dateTime: any) : number {
        const dividedByOneThousand = dateTime / 1000;

        const doubleToInt = Math.floor(dividedByOneThousand);
        return doubleToInt;
      }

    static sortinUserId(loginUserId : string, itemAddedUserId : string) {

        if(loginUserId < itemAddedUserId) {
            return loginUserId +'_' + itemAddedUserId;
        }else {
            return  itemAddedUserId + '_' + loginUserId;
        }
    }

    static log(log : any) {
        if (import.meta.env.VITE_APP_DEVELOPMENT === "true") {
            console.log(log);
        }
    }

    static clear() {
        if (import.meta.env.VITE_APP_DEVELOPMENT !== "true") {
            console.clear();
        }
    }

    static async waitingComponent(component, visibleRef = ref(true), retryLimit = 10, delay = 100 ) {
        visibleRef.value = true;
        let retryCount = 0;
        while(component.value == null) {
            await new Promise((resolve) => setTimeout(resolve, delay))
            if(retryCount++ > retryLimit) break;
        }
    }

    static addParamToCurrentUrl(param: String) {
        const url = window.location.origin + window.location.pathname + param;
        history.pushState(null, "", url);
    }

    static checkFlutterwaveCurrency(currency){
        const currencies = ['GBP', 'CAD', 'XAF', 'CLP', 'COP', 'EGP', 'EUR', 'GHS', 'GNF', 'KES', 'MWK', 'MAD', 'NGN', 'RWF', 'SLL', 'STD', 'ZAR', 'TZS', 'UGX', 'USD', 'XOF', 'ZMW'];

        return currencies.includes(currency);
    }

    static updateBrowserUrl(routeName : string, paramString : string) {
        const currentUrl = window.location.href;
        
        const basePath = currentUrl.split(routeName)[0];

        // Construct the new URL
        const newUrl = `${basePath}${routeName}?${paramString}`;
        window.history.replaceState({}, '', newUrl);
    }

    static formatPrice(appInfoStore, price) {
        const priceFormat = appInfoStore?.appInfo?.data?.mobileSetting?.price_format ?? '##,###.00';
        const hasDecimal = priceFormat.includes('.');
        const updatedPriceFormat = hasDecimal ? priceFormat : `${priceFormat}.0`;
        const formattedPrice = format(updatedPriceFormat, price);
        return hasDecimal ? formattedPrice : formattedPrice.split('.')[0];
    }
}
