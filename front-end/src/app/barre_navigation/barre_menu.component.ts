import { Component } from '@angular/core';
import { RouterModule } from '@angular/router';
import { ApiIdentificationService } from '../api/api_identification.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'barre-menu',
  standalone: true,
  imports: [RouterModule, CommonModule],
  template: `
    <div class="barre-navigation">
      <a class="logo" routerLink="/">Chum</a>

      <div *ngIf="!estConnecte; else elseBlock" class="connection">
        <a class="bouton-barre-navigation" routerLink="/connexion">Connexion</a>
        <div class="menu-tombant-contenu">
          <a routerLink="/connexion/identification">Se connecter</a>
          <a routerLink="/connexion/inscription">S'inscrire</a>
        </div>
      </div>
      <ng-template #elseBlock>
        <a class="bouton-barre-navigation" routerLink="/connexion">Déconnexion</a>
      </ng-template>
      
      <a class="bouton-barre-navigation" routerLink="/gestion">Gestion</a>
      <a class="bouton-barre-navigation actif" routerLink="/">Menu</a>
    </div>`
})
export class BarreMenuComponent 
{
  user: any;
  estConnecte: boolean = false;

  constructor(private apiIdentificationService: ApiIdentificationService) { }

  ngOnInit() 
  {
    this.user = this.apiIdentificationService.getUserInfo().user;
    this.estConnecte = this.apiIdentificationService.getUserInfo().isConnected;
  }
}
