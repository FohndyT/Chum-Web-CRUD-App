import { Component } from '@angular/core';
import { BarreConnexionComponent } from '../../barre_navigation/barre_connexion.component';
import { RouterModule, Router } from '@angular/router';
import { ApiIdentificationService } from '../../api/api_identification.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-identification',
  standalone: true,
  imports: [
    BarreConnexionComponent,
    RouterModule,
    CommonModule,
    FormsModule,
  ],
  templateUrl: './identification.component.html',
  styleUrl: './identification.component.css'
})
export class IdentificationComponent 
{
  identifiant: string = '';
  motDePasse: string = '';
  messageErreur: string | null = null;

  constructor(private apiIdentificationService: ApiIdentificationService, private router: Router) { }

  onSubmit()
  {
    this.apiIdentificationService.login(this.identifiant, this.motDePasse).subscribe(response => {
      if (response.success) 
      {
        this.router.navigate(['/']);
      } 
      else 
      {
        this.messageErreur = response.message;
      }
    });
  }
}
