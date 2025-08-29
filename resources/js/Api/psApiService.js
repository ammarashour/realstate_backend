import axios from 'axios';
import { createInertiaApp, Head, Link, usePage } from '@inertiajs/vue3';


let sub_domain_url = '';
let clearSlash = '';
// if(import.meta.env.PROD){
//     clearSlash =  usePage().props.dir;
// } else {
//     clearSlash =  import.meta.env.VITE_APP_DIR;
// }
clearSlash =  window.appConfig.VITE_APP_DIR; //import.meta.env.VITE_APP_DIR;
let subFolder = clearSlash.replaceAll("\\", "");

if(import.meta.env.PROD){

    if(subFolder != null && subFolder != '')
    {
        sub_domain_url = '/'  + window.appConfig.VITE_APP_DIR; //import.meta.env.VITE_APP_DIR;
    }
    else
    {
        sub_domain_url = '';
    }


}else{
    sub_domain_url = '';
}

const base_url = sub_domain_url+"/api/v1.0"
const custom_url = sub_domain_url
const category_url="/category/search"
const sub_cat_url="/sub_category/search"
const city_url="/location-city/search"
const township_url="/location-township/search"
const user_url="/user/search"
const customfields_url="/product/customize-header"
const existUser="/existuser"

const limit = "5"


export const getCategories = (offset,keyword,user_id,lang = 'en') => {

  return axios.post(base_url+category_url,{
    keyword:keyword,
    login_user_id: user_id,
          limit: limit,
          offset: offset,
          language_symbol: lang,
  }).then((response) => response).catch();
}

export const getSubCat = (offset,keyword,user_id,category_id) => {
    return axios.post(base_url+sub_cat_url+"?login_user_id="+user_id,{
        keyword: keyword ? keyword : "",
        category_id:category_id == 'all' ? '' : category_id,
              limit: limit,
              offset: offset,

      }).then((response) => response).catch();
}

export const getCities = (offset,keyword,user_id) => {
    return axios.post(base_url+city_url+"?login_user_id="+user_id,{
          keyword: keyword ? keyword : "",
          limit: limit,
          offset: offset,

      }).then((response) => response).catch();
}

export const getTownships = (offset,keyword,user_id,city_id) => {
  // alert(city_id)
    return axios.post(base_url+township_url+"?login_user_id="+user_id,{
        keyword: keyword ? keyword : "",
        city_id:city_id == 'all' ? '' : city_id,
        limit: limit,
        offset: offset,

      }).then((response) => response).catch();
}

export const getUsers = (offset,keyword,user_id) => {
  return axios.post(base_url+user_url+"?login_user_id="+user_id,{
      keyword: keyword ? keyword : "",
      limit: limit,
      offset: offset,

    }).then((response) => response).catch();
}

export const getPhoneCountryCodes = (offset,keyword,user_id) => {
    return axios.post(base_url+"/phone_country_code/search?language_symbol=en?login_user_id="+user_id+ "&limit=" + limit + "&offset=" + offset,{
        keyword: keyword ? keyword : "",

      }).then((response) => response).catch();
  }

export const getPurchaseOption = (offset,user_id) => {
  return axios.get(base_url+customfields_url+'/ps-itm00003/customize-details?login_user_id='+user_id+'&limit=5&offset='+offset).then((response) => response).catch();
}



export const getDealOption = (offset,user_id) => {
  return axios.get(base_url+customfields_url+'/ps-itm00007/customize-details?login_user_id='+user_id+'&limit=5&offset='+offset).then((response) => response).catch();
}

export const getItemTypeOption = (offset,user_id) => {
  return axios.get(base_url+customfields_url+'/ps-itm00002/customize-details?login_user_id='+user_id+'&limit=5&offset='+offset).then((response) => response).catch();
}

export const getCustomFields = (offset,keyword,user_id,customFieldModule) => {
    return axios.get(base_url+customfields_url+'/'+customFieldModule+'/customize-details?login_user_id='+user_id+'&limit=5&offset='+offset).then((response) => response).catch();
}

export const getExistUser = async (google_id,email) => {
    return axios.get(custom_url+existUser+'?google_id='+google_id+'&email='+email).then((response) => response).catch();
  }



