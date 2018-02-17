import { Injectable } from '@angular/core';
import {BehaviorSubject} from "rxjs/BehaviorSubject";
import {UserModel} from "./sqlite/user.model";
import {AuthGuard} from "./auth-guard";
import {Storage} from "@ionic/storage";

/*
  Generated class for the AuthOffline provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
  */
  @Injectable()
  export class AuthOffline implements AuthGuard{

    private _user = null;
    private _userSubject = new BehaviorSubject(null);
    private _userKey = 'userId';

    constructor(public userModel: UserModel, public storage: Storage) {}

    user(): Promise<Object>{
      return this.storage.get(this._userKey)
          .then(id => {
            return this.userModel.find(id)
          })
          .then(user => {
            this._user = user;
            this._userSubject.next(user);
            return user;
          });
    }

    userSubject(): BehaviorSubject<Object>{
      return this._userSubject;
    }

    login({email, password}): Promise<Object> {
      return this.userModel.findByField('email', email)
      .then((resultset) => {
        if(!resultset.rows.length){
          return Promise.reject('User not found');
        }
        this._user = resultset.rows.item(0);
        this._user.subscription_valid = true;
        this._userSubject.next(this._user);
        return this._user;
      });
    }

    check(): Promise<boolean>{
      return this.user().then(user => {
        return user !== null;
      });
    }

    logout(): Promise<any> {
      this._user = null;
      this._userSubject.next(null);
      return Promise.resolve(null);
    }

  }
