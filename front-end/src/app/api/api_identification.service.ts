import { Component, Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, BehaviorSubject, tap } from 'rxjs';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class ApiIdentificationService 
{
  private apiUrl = 'http://localhost:8000/api';
  //public estConnecte: boolean = false;
  private isConnectedSubject = new BehaviorSubject<boolean>(this.hasUser());

  constructor(private http: HttpClient, private router: Router) {}

  login(username: string, password: string): Observable<any> 
  {
    const headers = new HttpHeaders({'Content-Type': 'application/json'});

    return this.http.post<any>(`${this.apiUrl}/login`, { _username: username, _password: password }, { headers })
      .pipe(
        tap(response => {
          if (response.success) 
          {
            console.log("Connexion réussie");
            localStorage.setItem('user', JSON.stringify(response.user));
            this.isConnectedSubject.next(true);
          } 
          else 
          {
            console.log("Connexion échouée");
          }

          //this.estConnecte = response.success;
        })
      );
  }

  logout() 
  {
    const headers = new HttpHeaders({'Content-Type': 'application/json'});
    //this.estConnecte = false;

    return this.http.post('http://localhost:8000/api/logout', {}, { headers }).subscribe (
      response => {
        console.log('Logout successful');
        localStorage.removeItem('user');
        this.isConnectedSubject.next(false);
        this.router.navigate(['/']);
      },
      error => {
        console.error('Logout failed', error);
      }
    );
  }

  hasUser(): boolean {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    return user && Object.keys(user).length > 0;
  }

  getUserInfo() 
  {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const isConnected = user && Object.keys(user).length > 0;
    return { user, isConnected };
  }

  get isConnected$(): Observable<boolean> {
    return this.isConnectedSubject.asObservable();
  }
}
