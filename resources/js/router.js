import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)


import ServicesOrder from './views/service/ServicesOrder.vue'

//recursos propios
import Programs from './views/resources/Programs.vue'
import AddProgram from './views/resources/AddProgram.vue'
import AddStudentProgram from './views/resources/AddStudentProgram.vue'
import AddSaleProgram from './views/resources/AddSaleProgram.vue'
import AddConsumeProgram from './views/resources/AddConsumeProgram.vue'
import AddTutorProgram from './views/resources/AddTutorProgram.vue'
import AddAssetProgram from './views/resources/AddAssetProgram.vue'


//  |--------------------------------------------------------------------------
//  | Rutas API para el Sistema de Ventas de valores para Alumnos Admitidos
//  |--------------------------------------------------------------------------
import StudentSalesDay from './views/treasure/StudentSalesDay.vue'
import StudentManualSalesDay from './views/treasure/StudentManualSalesDay.vue'
import StudentSaleDetails from './views/treasure/StudentSaleDetails.vue'
import StudentManualSaleDetails from './views/treasure/StudentManualSaleDetails.vue'

//Solvencias
import DebtorsDocument from './views/solvency/DebtorsDocument.vue'
import Debts from './views/solvency/Debts.vue'
import AddDebts from './views/solvency/AddDebts.vue'
import EditDebts from './views/solvency/EditDebts.vue'
import ShowDebts from './views/solvency/ShowDebts.vue'
import RegularizeDebts from './views/solvency/RegularizeDebts.vue'
import AddRegularizeDebts from './views/solvency/AddRegularizeDebts.vue'

//Estados Financieros

import FinancialStatements from './views/document/FinancialStatements.vue'
import FinancialStatementDetails from './views/document/FinancialStatementDetails.vue'

//Sistema de Votaciones
import Tablets from './views/election/Tablets.vue'
import TabletDetails from './views/election/TabletDetails.vue'

//documentacion: memoriales
import RequestMemorial from './views/document/RequestMemorial.vue'
import RequestMemorialDetail from './views/document/RequestMemorialDetail.vue'

//granja universitaria
import FarmSaleDays from './views/farm/FarmSaleDays.vue'
import AddFarmSaleDay from './views/farm/AddFarmSaleDay.vue'
import StoreCustomerSaleDetail from './views/farm/StoreCustomerSaleDetail.vue'
import SaleDetailReport from './views/farm/SaleDetailReport.vue'
import FarmIncomeDays from './views/farm/FarmIncomeDays.vue'
import AddFarmIncomeDay from './views/farm/AddFarmIncomeDay.vue'
import FarmDropDays from './views/farm/FarmDropDays.vue'
import AddFarmDropDay from './views/farm/AddFarmDropDay.vue'
import AddFarmRegularizeDay from './views/farm/AddFarmRegularizeDay.vue'
import FarmRegularizeDays from './views/farm/FarmRegularizeDays.vue'
import AddFarmSummaryDay from './views/farm/AddFarmSummaryDay.vue'
import FarmSummaryDays from './views/farm/FarmSummaryDays.vue'
import FarmKardexProduct from './views/farm/FarmKardexProduct.vue'


//Almancenes
import Material from './views/store/Material'
import AddMaterial from './views/store/AddMaterial'
import EditMaterial from './views/store/EditMaterial'
import MovementMaterial from './views/store/MovementMaterial'

// Pages
import NotFound from './views/NotFound'
import Login from './views/Login'
import Logout from './views/Logout'
import Dashboard from './views/Dashboard'

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
import AddArchiveDetails from './views/archive/AddArchiveDetails'
import FileContainer from './views/archive/FileContainer'
import FileContainerDetails from './views/archive/FileContainerDetails'
import AddFileContainer from './views/archive/AddFileContainer'
import TypesArchive from './views/archive/TypesArchive'
import AddTypeArchive from './views/archive/AddTypeArchive'
import EditTypeArchive from './views/archive/EditTypeArchive'
import Booking from './views/archive/Booking'
import BookingDetails from './views/archive/BookingDetails'
import RequestDocument from './views/archive/Request'
import RequestDocumentDetails from './views/archive/RequestDetails'
import ArchiveDigitalDetails from './views/archive/ArchiveDigitalDetails'
import TransferContainerDetails from './views/archive/TransferContainerDetails'
//bienes e inventarios
import Inventory from './views/inventory/Inventory'
import Inventory2 from './views/inventory/Inventory2'
import EditInventory2 from './views/inventory/EditInventory2'
import InventoryDetail from './views/inventory/InventoryDetail'
import NewInventory from './views/inventory/NewInventory'
import NewInventoryDetail from './views/inventory/NewInventoryDetail'
import EditNewInventoryDetail from './views/inventory/EditNewInventoryDetail'
import Inventory2Detail from './views/inventory/Inventory2Detail'
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


import Assignments from './views/fixedasset/Assignments'
import AddAssignment from './views/fixedasset/AddAssignment'
import AssignmentsDetails from './views/fixedasset/AssignmentsDetails'
import AssignmentDetails2 from './views/fixedasset/AssignmentDetails2'
import PurchaseAssignment from './views/fixedasset/PurchaseAssignment'
import EditAssignmentsDetails from './views/fixedasset/EditAssignmentsDetails'

//tesoreria
import ValueTransactions from './views/treasure/ValueTransactions'
import TransactionsValue from './views/treasure/TransactionsValue'

import Solvency from './views/treasure/Solvency'
import SaleStudents from './views/treasure/SaleStudents'    //lista de dias de alumnos nuevos
import Students from './views/treasure/Students'            //alumnos nuevos
import AppendDebtors from './views/treasure/AppendDebtors'  //lista de dias de deudores
import Debtors from './views/treasure/Debtors'              //deudores
import HistoryTransactions from './views/treasure/HistoryTransactions'              //historial de transacciones

import TransactionsPersonal from './views/treasure/TransactionsPersonal'
import SaleInLine from './views/treasure/SaleInLine'
import SaleInLineDetail from './views/treasure/SaleInLineDetail'
import VerifySaleInLineDetail from './views/treasure/VerifySaleInLineDetail'

import GatewayPayments from './views/treasure/GatewayPayments'



//presupuestos individuales
import SinglesBudget from './views/singlebudget/SinglesBudget'
import SingleBudgetDetail from './views/singlebudget/SingleBudgetDetail'
import AddSingleBudget from './views/singlebudget/AddSingleBudget'

//import { component } from 'vue/types/umd'
import addNotification from './views/certificates/addNotification'
import AddSolvency from './views/certificates/AddSolvency'
import editTransactionDocuments from './views/certificates/editTransactionDocuments'

// Routes
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        /*
        {
            path: '/',
            name: 'home',
            component: Home,
            children: [
                { path: '', name: 'layout', component: Layout },
            ],
        },
        */
        {
            path: '/', //path: '/login',
            name: 'login',
            component: Login,
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
                //{ path: 'test', name: 'test', component: Test },

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
                //{ path: 'assets', name: 'assets', component: Assets },
                // enlaces para administrar los usuarios
                { path: 'users', name: 'users', component: Users },
                { path: 'users/add', name: 'adduser', component: AddUser },
                { path: 'users/:id', name: 'edituser', component: EditUser },
                { path: 'userprofiles', name: 'showuser', component: ShowUser },
                { path: 'users/profiles', name: 'edituserprofiles', component: EditUserProfiles },

                { path: 'servicesOrder', name: 'servicesorder', component: ServicesOrder },
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


                //  |--------------------------------------------------------------------------
                //  | Rutas API para Activos Fijos
                //  |--------------------------------------------------------------------------    

                { path: 'assignments', name: 'assignments', component: Assignments }, //lista de documentos de entrega activos fijos
                { path: 'assignments/:id', name: 'assignmentsdetails', component: AssignmentsDetails }, // documentos de entrega activos fijos impresion
                { path: 'assignments/purchase/:id', name: 'purchaseassignment', component: PurchaseAssignment },
                { path: 'assignment/add/:id', name: 'addassignment', component: AddAssignment }, // documentos de entrega activos fijos impresion
                { path: 'assignments2/:id', name: 'assignmentdetails2', component: AssignmentDetails2 }, // documentos de entrega activos fijos impresion
                { path: 'assignments/edit/:id', name: 'editassignmentsdetails', component: EditAssignmentsDetails }, // documentos de entrega activos fijos impresion

                { path: 'historytransactions', name: 'historytransactions', component: HistoryTransactions }, // historial de transacciones por persona
                //{ path: 'nuevaConvocatoria', name: 'nuevaConvocatoria', component: NuevaConvocatoria },//nueva convocatoria de documento
                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Recursos Propios
                //  |--------------------------------------------------------------------------    
                { path: 'programs', name: 'programs', component: Programs },
                { path: 'programs/add', name: 'addprogram', component: AddProgram },
                { path: 'programs/student/:id', name: 'addstudentprogram', component: AddStudentProgram },
                { path: 'programs/sale/:id', name: 'addsaleprogram', component: AddSaleProgram },
                { path: 'programs/consume', name: 'addtutorprogram', component: AddTutorProgram },
                { path: 'programs/asset', name: 'addconsumeprogram', component: AddConsumeProgram },
                { path: 'programs/tutor', name: 'addassetprogram', component: AddAssetProgram },                
                /*
                { path: 'CoursesPostgraduate', name: 'coursesPostgraduate', component: CoursesPostgraduate },
                { path: 'CoursesPostgraduate/add', name: 'addCoursePostgraduate', component: AddCoursePostgraduate },
                */
                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Estados Financieros
                //  |--------------------------------------------------------------------------    
                //  * EF1. Obtener la lista de estados financieros 
                { path: 'financialStatements', name: 'financialstatements', component: FinancialStatements },
                //  * M2. Obtener la lista de memoriales para su verificacion 
                { path: 'financialStatements/:id', name: 'financialstatementdetails', component: FinancialStatementDetails },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Tesoro
                //  |--------------------------------------------------------------------------    
                //  * T1. Obtener una lista de las transacciones realizadas de un usuario en Cajas. 
                { path: 'transactionspersonal', name: 'transactionspersonal', component: TransactionsPersonal },
                { path: 'saleinline', name: 'saleinline', component: SaleInLine },// lista de dias disponibles para crear las solicitudes
                { path: 'saleinline/:id', name: 'saleinlinedetail', component: SaleInLineDetail }, //lista de solicitudes por dia 
                { path: 'saleinline/:id/:request', name: 'verifysaleinlinedetail', component: VerifySaleInLineDetail }, //lista de solicitudes por dia 
                { path: 'gatewaypayments', name: 'gatewaypayments', component: GatewayPayments }, //lista de solicitudes por dia 

                //  * TE1. Obtiene las transacciones de un valor que se vende de acuerdo a un rango de fechas
                { path: 'ValueTransactions', name: 'valuetransactions', component: ValueTransactions },
                //  * TE2. Obtiene las transacciones de un valor que se vende de acuerdo a un rango de fechas por usuario asignado
                { path: 'TransactionsValue', name: 'transactionsvalue', component: TransactionsValue },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Archivos
                //  |--------------------------------------------------------------------------    
                //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
                { path: 'archive', name: 'archive', component: Archive },
                { path: 'archive2/add', name: 'addarchivedetails', component: AddArchiveDetails },
                { path: 'archive/:id', name: 'archivedetails', component: ArchiveDetails },
                { path: 'filecontainer', name: 'filecontainer', component: FileContainer },
                { path: 'filecontainer/:id', name: 'filecontainerdetails', component: FileContainerDetails },
                { path: 'filecontainer2/add', name: 'addfilecontainer', component: AddFileContainer },
                { path: 'typesarchive', name: 'typesarchive', component: TypesArchive },
                //  * A. Lista de las solicitudes de reserva de una persona
                { path: 'booking', name: 'booking', component: Booking },
                //  * A. Modulo para agregar nuevas solicitudes de reserva y prestamo de documentos
                { path: 'booking/add', name: 'bookingdetails', component: BookingDetails },
                //  * A. Modulo para prestar devolver las solicitudes de documentos
                { path: 'requestdocument', name: 'requestdocument', component: RequestDocument },
                //  * A. Modulo para cofirmar la solicitud unica  de prestar devolver las solicitudes de documentos
                { path: 'requestdocument/:id', name: 'requestdocumentdetails', component: RequestDocumentDetails },
                //  * A. Modulo para realizar la transferencia de archivos o de contenedores
                { path: 'transfercontainerdetails', name: 'transfercontainerdetails', component: TransferContainerDetails },
                { path: 'addtypearchive', name: 'addtypearchive', component: AddTypeArchive },
                { path: 'edittypearchive/:id', name: 'edittypearchive', component: EditTypeArchive, },
                { path: 'archive3/:id', name: 'archivedigitaldetails', component: ArchiveDigitalDetails, },
                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Almacenes
                //  |--------------------------------------------------------------------------    
                //  * M1. Obtiene la lista de materiales con una breve descripcion                
                { path: 'material', name: 'material', component: Material },
                //  * M2. Obtiene la lista de movimientos del material
                { path: 'material/movement/:id', name: 'movementmaterial', component: MovementMaterial },
                { path: 'material/add', name: 'addMaterial', component: AddMaterial },
                { path: 'material/:id', name: 'editMaterial', component: EditMaterial },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Granjas
                //  |--------------------------------------------------------------------------    
                //  * G1. Obtiene la lista de los dias de venta de los productos de la granja 
                { path: 'farmsaledays', name: 'farmsaledays', component: FarmSaleDays },
                //  * G2. Agregar un nuevo dia de venta de los productos de la granja
                { path: 'farmsaledays/add', name: 'addfarmsaleday', component: AddFarmSaleDay },
                //  * G3. Agregar ventas al dia de venta de los productos de la granja
                { path: 'farmdays/sale/:id', name: 'storecustomersaledetail', component: StoreCustomerSaleDetail },
                //  * G4. Detalle del dia de venta de los productos de la granja
                { path: 'farmdays/saledetail/:id', name: 'saledetailreport', component: SaleDetailReport },
                //  * G5. Agregar dia de ingreso de producto de la granja
                { path: 'farmincomedays', name: 'farmincomedays', component: FarmIncomeDays },
                //  * G6. Detalle del dia de los ingresos de los productos de la granja
                { path: 'farmdays/income/:id', name: 'addfarmincomeday', component: AddFarmIncomeDay },
                //  * G7. Agregar transacciones de las regularizaciones 
                { path: 'farmregularizedays', name: 'farmregularizedays', component: FarmRegularizeDays },
                //  * G8. Detalle de los dias de las regularizaciones de los productos a credito
                { path: 'farmdays/regularize/:id', name: 'addfarmregularizeday', component: AddFarmRegularizeDay },
                //  * G9. Agregar dia de baja de producto de la granja
                { path: 'farmdropdays', name: 'farmdropdays', component: FarmDropDays },
                //  * G10. Detalle del dia de bajas de los productos de la granja
                { path: 'farmdays/drop/:id', name: 'addfarmdropday', component: AddFarmDropDay },
                //  * G11. Movimiento de ventas entre fechas
                { path: 'farmkardexproduct', name: 'farmkardexproduct', component: FarmKardexProduct },
                //  * G12. Movimiento de ingreso,ventas,bajas de productos
                { path: 'farmsummarydays', name: 'farmsummarydays', component: FarmSummaryDays },
                //  * G13. Detalle del resumen de los dias de venta de productos de la granja
                { path: 'farmdays/summary/:id', name: 'addfarmsummaryday', component: AddFarmSummaryDay },


                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Solvencias
                //  |--------------------------------------------------------------------------    
                //  * SO1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
                { path: 'debtorsdocument', name: 'debtorsdocument', component: DebtorsDocument },
                //  * SO2. Agregar Deudas 
                { path: 'debts', name: 'debts', component: Debts },
                { path: 'debts/add', name: 'adddebts', component: AddDebts },
                { path: 'debts/:id', name: 'editdebts', component: EditDebts },
                { path: 'debts/show/:id', name: 'showdebts', component: ShowDebts },
                //  * SO3. Regularizar Deudas
                { path: 'regularize', name: 'regularizedebts', component: RegularizeDebts },
                { path: 'regularize/add', name: 'addregularizedebts', component: AddRegularizeDebts },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Modulo de Presupuestos Individuales
                //  |--------------------------------------------------------------------------    
                //  * PIN1. Obtener una lista de los presupuestos individuales de el recurso utilizado.
                { path: 'singlesbudget', name: 'singlesbudget', component: SinglesBudget },
                //  * PIN(R1). Ruta para ir al modulo del presupuesto individual.
                { path: 'singlebudgetdetail/:id_single', name: 'singlebudgetdetail', component: SingleBudgetDetail },
                //  * PIN(R2). Ruta para ir al modulo para un nuevo presupuesto individual.
                { path: 'singlebudget/add', name: 'addsinglebudget', component: AddSingleBudget },

                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Modulo de Memoriales Universitarios
                //  |--------------------------------------------------------------------------    
                //  * M1. Obtener una lista de los memoriales solicitados.
                { path: 'requestMemorial', name: 'requestmemorial', component: RequestMemorial },
                //  * M2. Obtener la lista de memoriales para su verificacion 
                { path: 'requestMemorial/:id', name: 'requestmemorialdetail', component: RequestMemorialDetail },
                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Modulo de Elecciones
                //  |--------------------------------------------------------------------------    
                //  * M1. Obtener una lista de los memoriales solicitados.
                { path: 'tablets', name: 'tablets', component: Tablets },
                //  * M2. Obtener la lista de memoriales para su verificacion 
                { path: 'tablets/:id', name: 'tabletdetails', component: TabletDetails },


                //  |--------------------------------------------------------------------------
                //  | Rutas API para el Sistema de Ventas de valores para Alumnos Admitidos
                //  |--------------------------------------------------------------------------
                { path: 'StudentSalesDay', name: 'studentsalesday', component: StudentSalesDay },
                { path: 'StudentManualSalesDay', name: 'studentmanualsalesday', component: StudentManualSalesDay },
                { path: 'StudentSalesDay/sale/:id', name: 'studentsaledetails', component: StudentSaleDetails }, //alumnos nuevos
                { path: 'StudentManualSalesDay/manual/:id', name: 'studentmanualsaledetails', component: StudentManualSaleDetails }, //alumnos nuevos
                
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