import { Component } from '@angular/core';
import { BarreConnexionComponent } from '../../barre_navigation/barre_connexion.component';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-inscription',
  standalone: true,
  imports: [
    BarreConnexionComponent,
    RouterModule,
  ],
  templateUrl: './inscription.component.html',
  styleUrl: './inscription.component.css'
})
export class InscriptionComponent {

}
