import User from "../User";


export default class FacebookUserHolder {
 
    firebaseUser: User = new User(); 
    facebookUser : string = '';

    FacebookUserHolder() {
        this.firebaseUser = new User(); 
        this.facebookUser = '';

    }


   
}