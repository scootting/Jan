<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::post('login', 'GeneralController@searchUser');

Route::group([
    'middleware' => 'jwt.auth',
], function () {
    Route::post('logout', 'GeneralController@logoutUser');
    Route::post('profiles', 'GeneralController@registerUserProfiles');
    Route::post('years', 'GeneralController@registerUserYears');

    // *** - rutas para crear, editar, mostrar, buscar a las personas - ***
    // *** - Buscar - ***
    Route::post('persons', 'GeneralController@getPersonsByDescription');

    Route::post('doc/upload', 'GeneralController@uploadPDF');

    // *** - rutas para crear, editar, mostrar, buscar a los usuarios del sistema - ***
    Route::post('users', 'GeneralController@getUsersByDescription');
    Route::post('user', 'GeneralController@storeUser');
    Route::get('user/{id}', 'GeneralController@getUserById');
    /*
    Route::post('upload', 'FileController@uploadFile');
     */
    //Listar activos por unidad
    Route::get('inventory/show/{cod_soa}', 'InventoryController@getOfficeByCodSoa');
    Route::get('inventory/sub_offices/{cod_soa}', 'InventoryController@getSubOfficesByCodSoa');
    Route::get('inventory/cargos/{cod_soa}', 'InventoryController@getCargosByCodSoa');
    Route::get('inventory/responsables/{cod_soa}', 'InventoryController@getResponsablesByCodSoa');
    Route::get('inventory/activosByFilter/{cod_soa}', 'InventoryController@getActivosByFilter');
    Route::get('inventory/{gestion}', 'InventoryController@getOffices');
    Route::get('descargando/{cod_soa}', 'InventoryController@getReport');
    Route::get('inventarioDetalle/', 'InventoryController@getReportDetalle');
    Route::get('inventarioGeneral/', 'InventoryController@getReportGeneral');
    Route::get('generarReporte/', 'InventoryController@getReport');

    //rutas de inventarios 2
    Route::get('inventory2/unidad', 'InventoryController@getUnidad');
    Route::get('inventory2/sub_unidad', 'InventoryController@getSubUnidad');
    Route::get('inventory2/cargos', 'InventoryController@getCargos');
    Route::get('inventory2/responsables', 'InventoryController@getResponsables');
    Route::get('inventory2/responsables2', 'InventoryController@getResponsablesByUnidad');
    Route::get('inventory2/encargados', 'InventoryController@getEncargados');
    Route::post('inventory2/save', 'InventoryController@saveNewInventory');
    Route::post('inventory2/saveDataDetail', 'InventoryController@saveDatasDetail');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el sistema de Inventarios para Bienes de Uso
    //  |--------------------------------------------------------------------------
    //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
    Route::post('inventory2/', 'InventoryController@getInventories');
    //  * 2. Imprimir el reporte del inventario general o detallado de el recurso utilizado.
    Route::get('inventoryReport/', 'InventoryController@inventoryReport');
    //  * 3. Obtener una lista de activos fijos para el inventario utilizado.
    Route::post('getActivesByInventory/', 'InventoryController@getActivesByInventory');
    //  * 4. Obtener una lista de estados por cada activo fijo utilizado.
    Route::get('getStatesByActive/', 'InventoryController@getStatesByActive');
    //  * 5. Guardar los detalles determinados para cada activo fijo del inventario.
    Route::post('saveActiveDetail/', 'InventoryController@saveActiveDetail');

    Route::get('inventory2/edit/{id}', 'InventoryController@getInventory');
    Route::post('inventory2/saveChange', 'InventoryController@saveChangeDocInventory');
    Route::get('inventory2/doc_detail_by_active/{id}', 'InventoryController@getDocDetailByActivoId');
    Route::get('inventory2/search/{doc_cod}', 'InventoryController@getActivesForDocInv');
    Route::post('inventory2/verificar', 'InventoryController@changeStateInventory');
    Route::post('inventory2/saveImage', 'InventoryController@saveImages');
    Route::get('inventory2/image/{id}', 'InventoryController@getImagesById');
    //para cargar las imagenes de los activos
    Route::post('inventory2/upload', 'InventoryController@uploadImage');
    Route::get('inventory2/delete/upload-folder/{file}', 'InventoryController@deleteFile');
    Route::get('inventory2/download/upload-folder/{file}', 'InventoryController@downloadFile');
    //rutas de re asignacion de activos
    Route::get('reasignacion/', 'InventoryController@SearchActivo');
    Route::get('activo/partidas', 'InventoryController@getPartidas');
    Route::get('activo/contable', 'InventoryController@getContable');
    Route::get('activo/nro', 'InventoryController@getlastNroDoc');
    Route::get('activo/controlTrue', 'InventoryController@controlTrue');
    Route::get('activo/cargos', 'InventoryController@getAllCargos');
    Route::get('reasignacion/edit/{id}', 'InventoryController@getActive');
    Route::post('reasignacion/save', 'InventoryController@saveChangeActive');
    Route::post('newactive/save', 'InventoryController@saveNewActive');
    Route::get('listNroDoc', 'InventoryController@getListNroDoc');
    Route::post('selectActivebyDocument', 'InventoryController@getActivesbyDocument');
    Route::get('reportSelectedActive/', 'InventoryController@getReportSelectedActive');
    Route::get('inventoryReportGral/', 'InventoryController@informeGeneral');
    Route::get('inventoryReportFalse/', 'InventoryController@inventarioFalse');

    //** Guardar nueva convocatoria */
    Route::get('newCall/nro', 'GeneralController@getNewCodDocument');
    Route::post('newCall/save', 'GeneralController@saveNewCall');

    Route::post('documents', 'DocumentController@getDocumentsByOffice');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para al Administracion General del Sistema
    //  |--------------------------------------------------------------------------
    //  * S1. Obtiene la informacion de la persona con el numero de carnet
    Route::get('person/{id}', 'GeneralController@getPersonById');
    //  * S2. Guardar la informacion de una nueva persona que no se encuentra registrada.
    //  * S3. Actualiza la informacion de una persona que se encuentra registrada.
    Route::post('person', 'GeneralController@storePerson');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Tesoro
    //  |--------------------------------------------------------------------------
    //  * T1. Obtener una lista de las transacciones realizadas de un usuario en Cajas.
    Route::post('getTransactionsByPerson', 'TreasureController@getTransactionsByPerson');

    // *** - Tesoreria - Rutas para la venta de alumnos nuevos - ***
    // *** - Buscar por su carnet de identidad - ***
    Route::post('getDataOfStudentById', 'TreasureController@getDataOfStudentById');
    // *** - Buscar los valores pertenecientes a un tramite - ***
    Route::post('valuesprocedure', 'TreasureController@getValuesProcedure');

    // *** - Obtener el reporte correspondiente a los valores vendidos para alumnos nuevos - ***
    Route::get('reports/', 'TreasureController@getReportValuesQr');
    // *** - Obtener el reporte correspondiente a los valores vendidos para alumnos nuevos por dia - ***
    Route::get('reportDetailStudents/', 'TreasureController@getReportDetailStudents');

    // *** - Almacenar - ***
    Route::post('storeTransactionsByStudents', 'TreasureController@storeTransactionsByStudents');
    // *** - Obtener los dias para la venta de valores de un usuario - ***
    Route::post('getSaleOfDaysByDescription', 'TreasureController@getSaleOfDaysByDescription');
    Route::post('addSaleOfDay', 'TreasureController@addSaleOfDay');
    // *** - Obtener los dias para la venta de valores de un usuario - ***
    Route::post('getSaleOfDayById', 'TreasureController@getSaleOfDayById');
    Route::post('getValueById', 'TreasureController@getValueById');

    // *** - Obtener las transacciones por gestion - ***
    Route::post('getAllTransactionsByYear', 'TreasureController@getAllTransactionsByYear');
    // *** - Anula la transaccion - ***
    Route::post('cancelTransactionById', 'TreasureController@cancelTransactionById');

    // *** -  - ***
    Route::post('documentFixedAssets', 'FixedAssetController@getDocumentFixedAssetByYear');
    // *** -  - ***
    Route::post('selectedFixedAssetsbyDocument', 'FixedAssetController@getFixedAssetsbyDocument');
    //Route::get('reportSelectedFixedAssets/{id}', 'FixedAssetController@getReportSelectedFixedAssets');
    Route::get('reportSelectedFixedAssets/', 'FixedAssetController@getReportSelectedFixedAssets');
    Route::get('reportSelectedFixedAssets2/', 'FixedAssetController@getReportSelectedFixedAssets2');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Archivos
    //  |--------------------------------------------------------------------------
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    Route::post('archive', 'ArchiveController@getArchivesByDescription');
    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    Route::post('getDocumentsbyArchive/', 'ArchiveController@getDocumentsbyArchive');
    //  * A3. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    Route::post('getTypesDocument/', 'ArchiveController@getTypesDocument');
    //  * A4. Obtiene la lista de contenedores y ubicaciones con una breve descripcion
    Route::post('container', 'ArchiveController@getContainerByDescription');
    //  * A5. Obtiene la lista de archivos que pertenecen a un contenedor
    Route::post('getArchivesByContainer/', 'ArchiveController@getArchivesByContainer');
    //  * A6. Obtiene la lista de contenedores para archivar con una breve descripcion
    Route::post('fileContainer', 'ArchiveController@getFileContainerByDescription');
    //  * A7. Guardar un nuevo archivo, contenedor o ubicacion
    Route::post('file/', 'ArchiveController@storeFileContainer');

    //  * A1. Obtiene la lista de materiales con una breve descripcion
    Route::post('material', 'StoreController@getMaterialsByDescription');
    Route::post('addMaterial', 'StoreController@storeMaterial');
    Route::post('editMaterial', 'StoreController@storeMaterial');
    Route::get('EditMaterial/{id}', 'StoreController@getMaterialById');
    
    
});
