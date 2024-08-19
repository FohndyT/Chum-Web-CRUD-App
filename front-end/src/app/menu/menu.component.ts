import { Component, OnInit } from '@angular/core';
import { BarreMenuComponent } from '../barre_navigation/barre_menu.component';
import { RouterModule } from '@angular/router';
import { ApiIdentificationService } from '../api/api_identification.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-menu',
  standalone: true,
  imports: [
    BarreMenuComponent, 
    RouterModule,
    CommonModule,
  ],
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit
{
  user: any;
  estConnecte: boolean = false;

  constructor(private apiIdentificationService: ApiIdentificationService) { }

  ngOnInit() 
  {
    this.estConnecte = this.apiIdentificationService.hasUser(); // Initial check
    this.user = this.apiIdentificationService.getUserInfo().user;

    // Subscribe to changes in connection status
    this.apiIdentificationService.isConnected$.subscribe(isConnected => {
      this.estConnecte = isConnected;
      this.user = isConnected ? this.apiIdentificationService.getUserInfo().user : null;
    });
  }
}
