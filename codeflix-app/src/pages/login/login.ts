import {Component} from '@angular/core';
import {IonicPage, MenuController, NavController, NavParams, ToastController} from 'ionic-angular';
import "rxjs/add/operator/toPromise";
import {Auth} from "../../providers/auth";
import {HomePage} from "../home/home";


/**
 * Generated class for the LoginPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
@IonicPage()
@Component({
    selector: 'page-login',
    templateUrl: 'login.html',
})
export class LoginPage {

    user = {
        email: null,
        password: null
    };

    constructor(public navCtrl: NavController,
                public menuController: MenuController,
                public navParams: NavParams,
                public toastCtrl: ToastController,
                private auth: Auth) {
        this.menuController.enable(false);
    }

    ionViewDidLoad() {
        console.log('ionViewDidLoad LoginPage');
    }

    login() {
        this.auth.login(this.user)
            .then(() => {
                this.afterLogin();
            })
            .catch((error) => {
                let toast = this.toastCtrl.create({
                    message: 'E-mail e/ou senha inválidos.',
                    duration: 3000,
                    position: 'bottom',
                    cssClass: 'toast-login-error'
                });

                toast.present();
            });
    }

    afterLogin(){
        this.menuController.enable(true);
        this.navCtrl.push(HomePage);
    }

}
