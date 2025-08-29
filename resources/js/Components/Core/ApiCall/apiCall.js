import axios from 'axios';



export const getCategories = (offset,keyword) => {
  return axios.post("/api/v1.0/category/search",{
    keyword:keyword,
    login_user_id: '1',
          limit: '5',
          offset: offset
  }).then((response) => response).catch();
}
export const getSubCat = (offset,keyword) => {
  return axios.post("/api/v1.0/category/search",{
    keyword:keyword,
    login_user_id: '1',
          limit: '5',
          offset: offset
  }).then((response) => response);
}



