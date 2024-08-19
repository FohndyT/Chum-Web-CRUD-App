import { Routes } from '@angular/router';
import { MenuComponent } from './menu/menu.component';
import { GestionComponent } from './gestion/gestion.component';
import { IdentificationComponent } from './connexion/identification/identification.component';
import { InscriptionComponent } from './connexion/inscription/inscription.component';
import { ConnexionComponent } from './connexion/connexion.component';

const routeConfig: Routes = [
    {
        path: '',
        component: MenuComponent,
        title: 'Chum - Menu'
    },
    {
        path: 'gestion',
        component: GestionComponent,
        title: 'Chum - Gestion'
    },
    {
        path: 'connexion',
        component: ConnexionComponent,
        title: 'Chum - Connexion'
    },
    {
        path: 'connexion/identification',
        component: IdentificationComponent,
        title: 'Chum - Identification'
    },
    {
        path: 'connexion/inscription',
        component: InscriptionComponent,
        title: 'Chum - Inscription'
    }
];

export default routeConfig;