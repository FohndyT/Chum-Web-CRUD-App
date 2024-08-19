import { Component } from '@angular/core';
import { RouterOutlet, RouterModule } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    RouterOutlet, 
    RouterModule, 
  ],
  template:`<router-outlet></router-outlet>`,
  styleUrl: './app.component.css'
})
export class AppComponent 
{
  title = 'Chum';
}