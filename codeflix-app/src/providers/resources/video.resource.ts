import {Injectable} from '@angular/core';
import 'rxjs/add/operator/map';
import {AuthHttp} from "angular2-jwt";
import {Observable} from "rxjs/Observable";
import {Env} from "../../models/env";
import {RequestOptions, URLSearchParams} from "@angular/http";
import {VideoAdapter} from "../videos/video-adapter";

declare var ENV: Env;

/*
  Generated class for the VideoResourceProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class VideoResource implements VideoAdapter{

    constructor(public http: AuthHttp) {}

    latest(page: number, search: string): Observable<any> {
        let params = new URLSearchParams();
        params.set('page', page+'');
        params.set('include', 'serie_title,categories_name');
        params.set('search', search);

        let requestOptions = new RequestOptions({params});
        return this.http
            .get(`${ENV.APP_URL}/videos`, requestOptions)
            .map(response => response.json());
    }

    get(id: number): Observable<any>{
        let params = new URLSearchParams();
        params.set('include', 'serie_title,categories_name');

        let requestOptions = new RequestOptions({params});
        return this.http
            .get(`${ENV.APP_URL}/videos/${id}`, requestOptions)
            .map(response => {
                let data = response.json();
                let video = data.data;
                video.serie_title = typeof video.serie_title == 'undefined' ? null : data.data.serie_title.data.title;
                video.categories_name = data.data.categories_name.data.name;
                return video;
            });
    }

}
