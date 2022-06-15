import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)


//Almancenes
import Material from './views/store/Material'
import AddMaterial from './views/store/AddMaterial'
import EditMaterial from './views/store/EditMaterial'

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'
import Assets from './views/FixedAssets'
import AddNotDocument from './views/clients/AddNotDocument'

//Jessica
import Test from './views/clients/Test'

//usuario
import Users from './views/application/Users'
import AddUser from './views/application/AddUser'
import EditUser from './views/application/EditUser'
import ShowUser from './views/application/ShowUser'
import EditUserProfiles from './views/application/EditUserProfiles'

//certificado de diplomados
import AddGraduateCertificate from './views/document/AddGraduateCertificate'

//persona
import Persons from './views/application/Persons'
import AddPerson from './views/application/AddPerson'
import EditPerson from './views/application/EditPerson'
import Welcome from './views/Welcome'
import Home from './views/Home'
import Layout from './views/Layout'

//archivos
import Archive from './views/archive/Archive'
import ArchiveDetails from './views/archive/ArchiveDetails'
import FileContainer from './views/archive/FileContainer'
import FileContainerDetails from './views/archive/FileContainerDetails'
import AddArchive from './views/archive/AddArchive'


//bienes e inventarios
import Inventory from './views/inventory/Inventory'
import Inventory2 from './views/inventory/Inventory2'
import EditInventory2 from './views/inventory/EditInventory2'
import InventoryDetail from './views/inventory/InventoryDetail'
import NewInventory from './views/inventory/NewInventory'
import NewInventoryDetail from './views/inventory/NewInventoryDetail'
import EditNewInventoryDetail from './views/inventory/EditNewInventoryDetail'
import Inventory2Detail from './views/inventory/Inventory2Detail'
import Formalities from './views/Formalities'
import AddTaxExemption from './views/clients/AddTaxExemption'
import Active from './views/inventory/Active'
import CreateActive from './views/inventory/CreateActive'
import EditActive from './views/inventory/EditActive'
import DocumentQR from './views/inventory/DocumentQR'
import SelectActiveByDocument from './views/inventory/SelectActiveByDocument'
import NewActive from './views/inventory/NewActive'
import ImgDetail from './views/inventory/ImgDetail'
//activos fijos
import DocumentsFixedAssets from './views/fixedasset/DocumentsFixedAssets'
import SelectedFixedAssetsByDocument from './views/fixedasset/SelectedFixedAssetsByDocument'

//tesoreria
import Solvency from './views/treasure/Solvency'
import SaleStudents from './views/treasure/SaleStudents'    //lista de dias de alumnos nuevos
import Students from './views/treasure/Students'            //alumnos nuevos
import AppendDebtors from './views/treasure/AppendDebtors'  //lista de dias de deudores
import Debtors from './views/treasure/Debtors'              //deudores
import HistoryTransactions from './views/treasure/HistoryTransactions'              //historial de transacciones

import TransactionsPersonal from './views/treasure/TransactionsPersonal'


//clientes 
import LoginClient from './views/clients/Login'
import DashboardClient from './views/clients/Dashboard'

import NuevaConvocatoria from './views/NewCall'
//import { component } from 'vue/types/umd'
import addNotification from './views/certificates/addNotification'
import AddSolvency from './views/certificates/AddSolvency'
import editTransactionDocuments from './views/certificates/editTransactionDocuments'
import { Container } from 'element-ui'

// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                { path: '', name: 'layout', component: Layout },
                { path: '/formalities', name: 'formalities', component: Formalities },
                { path: '/taxExemption', name: 'addTaxExemption', component: AddTaxExemption },
                { path: '/addNotDocument', name: 'addnotdocument', component: AddNotDocument },
            ],
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/client', name: 'loginclient', component: LoginClient
        },
        {
            path: '/client/:id', name: 'dashboardclient', component: DashboardClient,
            children: [
                { path: '', name: 'welcome2', component: Welcome },
                { path: 'nodebt', name: 'addnotdocument2', component: AddNotDocument },
            ],

        },

        {
            path: '/logout',
            name: 'logout',
            component: Logout,
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/api', // /dashboard  /api/assets /api/profiles
            name: 'dashboard',
            component: Dashboard,
            children: [
                // UserHome will be rendered inside User's <router-view>
                // when /user/:id is matched0
                { path: 'test', name: 'test', component: Test },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para al Administracion General del Sistema
                //  |--------------------------------------------------------------------------    
                //  * S1. Obtiene la informacion de la persona con el numero de carnet
                { path: 'person/:id', name: 'editperson', component: EditPerson },
                //  * S2. Guardar la informacion de una nueva persona que no se encuentra registrada.
                //  * S3. Actualiza la informacion de una persona que se encuentra registrada.
                { path: 'person/add', name: 'addperson', component: AddPerson },

                // enlaces para administrar las personas
                { path: 'persons', name: 'persons', component: Persons },


                { path: '', name: 'welcome', component: Welcome },
                { path: 'assets', name: 'assets', component: Assets },
                // enlaces para administrar los usuarios
                { path: 'users', name: 'users', component: Users },
                { path: 'user/add', name: 'adduser', component: AddUser },
                { path: 'user/:id', name: 'edituser', component: EditUser },
                { path: 'user/show', name: 'showuser', component: ShowUser },
                { path: 'user/profiles', name: 'edituserprofiles', component: EditUserProfiles },

                //Modulo para administrar y gestionar inventarios de bienes de uso
                // Lionel - enlace a la lista de inventarios para la gestion 
                { path: 'inventory2', name: 'inventory2', component: Inventory2 },
                // Lionel - enlace a la lista de activos de un inventario para la gestion 
                { path: 'inventory2/:id_inventory', name: 'inventory2detail', component: Inventory2Detail },


                { path: 'inventory', name: 'inventory', component: Inventory },
                { path: 'inventory/:soa', name: 'inventorydetail', component: InventoryDetail },
                { path: 'inventory2/:id', name: 'editinventory2', component: EditInventory2 },
                { path: 'newinventory', name: 'newinventory', component: NewInventory },
                { path: 'newinventory/:soa', name: 'newinventorydetail', component: NewInventoryDetail },
                { path: 'imgDetail/:id', name: 'imgdetail', component: ImgDetail },
                { path: 'editnewinventory/:id', name: 'editnewinventorydetail', component: EditNewInventoryDetail },
                { path: 'active', name: 'active', component: Active },
                { path: 'createactive', name: 'createactive', component: CreateActive },
                { path: 'active/:id', name: 'editactive', component: EditActive },
                { path: 'newactive/:soa', name: 'newactive', component: NewActive },
                { path: 'documentqr', name: 'documentqr', component: DocumentQR },
                { path: 'documentqr/:id', name: 'selectactivebydocument', component: SelectActiveByDocument },

                { path: 'addgraduatecertificate', name: 'addgraduatecertificate', component: AddGraduateCertificate },

                //enlaces para la administracion de los certificados de no deuda, solvencias
                { path: 'addnotification', name: 'addnotification', component: addNotification }, // agregar la convocatoria para un certificado de no deuda
                { path: 'addsolvency', name: 'addsolvency', component: AddSolvency }, // agregar solvencia para tramite
                { path: 'edittransactiondocuments', name: 'edittransactiondocuments', component: editTransactionDocuments }, //editar el estado de un documento


                //enlaces para la administracion de paginas de tesoreria
                { path: 'solvency', name: 'solvency', component: Solvency }, // solvencias
                { path: 'salestudents', name: 'salestudents', component: SaleStudents }, //dia de alumnos nuevos
                { path: 'salestudents/:id', name: 'students', component: Students }, //alumnos nuevos
                { path: 'appenddebtors', name: 'appenddebtors', component: AppendDebtors }, //dia de deudores
                { path: 'appenddebtors/:id', name: 'debtors', component: Debtors }, // deudores
                { path: 'documentsfixedassets', name: 'documentsfixedassets', component: DocumentsFixedAssets }, //lista de documentos de entrega activos fijos
                { path: 'documentsfixedassets/:id', name: 'selectedFixedAssetsByDocument', component: SelectedFixedAssetsByDocument }, // documentos de entrega activos fijos impresion
                { path: 'historytransactions', name: 'historytransactions', component: HistoryTransactions }, // historial de transacciones por persona
                { path: 'nuevaConvocatoria', name: 'nuevaConvocatoria', component: NuevaConvocatoria },//nueva convocatoria de documento

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Tesoro
                //  |--------------------------------------------------------------------------    
                //  * T1. Obtener una lista de las transacciones realizadas de un usuario en Cajas. 
                { path: 'transactionspersonal', name: 'transactionspersonal', component: TransactionsPersonal },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Archivos
                //  |--------------------------------------------------------------------------    
                //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
                { path: 'archive', name: 'archive', component: Archive },
                { path: 'archive/:id', name: 'archivedetails', component: ArchiveDetails },
                { path: 'filecontainer', name: 'filecontainer', component: FileContainer },
                { path: 'filecontainer/:id', name: 'filecontainerdetails', component: FileContainerDetails },
                { path: 'addArchive/:type', name: 'addarchive', component: AddArchive },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Almacenes
                //  |--------------------------------------------------------------------------    
                //  * S1. Obtiene la lista de materiales con una breve descripcion
                { path: 'material', name: 'material', component: Material },
                { path: 'material/add', name: 'addmaterial', component: AddMaterial },
                { path: 'material/:id', name: 'editmaterial', component: EditMaterial },
            ],
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/404',
            name: '404',
            component: NotFound,
        },
        {
            path: '*',
            redirect: '/404',
        },
    ],
});

export default router