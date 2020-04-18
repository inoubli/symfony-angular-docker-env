import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';@Component({

  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  
  title = 'application';
  commandes: any = [];
  constructor(private http: HttpClient) { }

  

  getCommandes() {
    this.http.get('http://localhost:82/api/commande/list')
      .subscribe(data => {
        for (const d of (data as any)) {
          this.commandes.push({
            ref: d.ref,
            date: d.date
          });
        }
        console.log(this.commandes);
      });
  }


  ngOnInit(){

    this.getCommandes();
    

  }
  
}



