import { Component } from '@angular/core';
import { BarreGestionComponent } from '../barre_navigation/barre_gestion.component';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-gestion',
  standalone: true,
  imports: [
    BarreGestionComponent, 
    RouterModule, 
  ],
  templateUrl: './gestion.component.html',
  styleUrl: './gestion.component.css'
})
export class GestionComponent {
  
}
