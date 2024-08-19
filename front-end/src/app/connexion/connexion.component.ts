import { Component, OnInit } from '@angular/core';
import { RouterModule } from '@angular/router';
import { BarreConnexionComponent } from '../barre_navigation/barre_connexion.component';
import { ApiIdentificationService } from '../api/api_identification.service';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-connexion',
  standalone: true,
  imports: [
    BarreConnexionComponent, 
    RouterModule,
    CommonModule
  ],
  templateUrl: './connexion.component.html',
})
export class ConnexionComponent implements OnInit
{
  user: any;
  estConnecte: boolean = false;

  constructor(private apiIdentificationService: ApiIdentificationService, private router: Router) {}

  ngOnInit() 
  {
    this.estConnecte = this.apiIdentificationService.hasUser(); // Initial check
    this.user = this.apiIdentificationService.getUserInfo().user;

    this.apiIdentificationService.isConnected$.subscribe(isConnected => {
      this.estConnecte = isConnected;
      this.user = isConnected ? this.apiIdentificationService.getUserInfo().user : null;
    });
  }

  deconnexion()
  {
    this.apiIdentificationService.logout();
  }
}
