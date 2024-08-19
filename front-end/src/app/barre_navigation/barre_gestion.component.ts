import { Component } from '@angular/core';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'barre-gestion',
  standalone: true,
  imports: [
    RouterModule, 
  ],
  template: `
    <div class="barre-navigation">
      <a class="logo" routerLink="/">Chum</a>

      <div class="connection">
        <a class="bouton-barre-navigation" routerLink="/connexion">Connexion</a>
        <div class="menu-tombant-contenu">
          <a routerLink="/connexion/identification">Se connecter</a>
          <a routerLink="/connexion/inscription">S'inscrire</a>
        </div>
      </div>

      <a class="bouton-barre-navigation actif" routerLink="/gestion">Gestion</a>
      <a class="bouton-barre-navigation" routerLink="/">Menu</a>
    </div>`
})
export class BarreGestionComponent {

}
