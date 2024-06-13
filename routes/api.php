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
    Route::get('generarReporte2/', 'InventoryController@getReportXML');

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
    //  * I5. Guardar los detalles determinados para cada activo fijo del inventario.
    Route::post('storeActiveDetail/', 'InventoryController@storeActiveDetail');

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
    //  * T2. Obtener una lista de las ventas en linea solicitadas durante la gestion.
    Route::post('getSaleInLineDetail', 'TreasureController@getSaleInLineDetail');
    //  * T4. Obtener el detalle de una solicitud utilizando su id.
    Route::post('getDataRequestById', 'TreasureController@getDataRequestById');
    //  * T22. Agrega un nuevo dia para la venta de valores para estudiantes nuevos
    Route::post('storeDayForSale', 'TreasureController@storeDayForSale');
    //  * T40 trae el documento digitalizado
    Route::get('getDigitalBoucher', 'TreasureController@getDigitalBoucher');
    //  *  T41. Guardar las verificaciones realizadas cada solicitud
    Route::post('storeRequestSaleInLine', 'TreasureController@storeRequestSaleInLine');
    //  *  T42. Guardar las verificaciones realizadas cada solicitud
    Route::post('getTransactionsByDay', 'TreasureController@getTransactionsByDay');

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

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Ventas en Linea de la Division de Tesoro
    //  |--------------------------------------------------------------------------
    //  * T30. Obtienes los dias de venta en linea para manhattan, nottingham, vancouber
    Route::post('getOnlineSalesDays', 'TreasureController@getOnlineSalesDays');
    //  * T31. Imprime el detalle de ventas en linea para manhattan, nottingham, vancouber
    Route::get('reportOnlineSales', 'TreasureController@reportOnlineSales');

    //  * T40. Obtienes los pagos
    Route::post('getGatewayPayments', 'TreasureController@getGatewayPayments');
    //  * T41. Imprime el detalle de ventas en linea para manhattan, nottingham, vancouber
    Route::get('getReportGatewayPayments', 'TreasureController@getReportGatewayPayments');

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
    //  * A7. Guardar contenedor o ubicacion
    Route::post('storeFileContainer', 'ArchiveController@storeFileContainer');
    //  * A8. Obtiene un tipo de archivo en especifico
    Route::get('typeArchive/{id}', 'ArchiveController@getTypeArchiveById');
    //  * A9. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    Route::post('getTypesDocumentById/', 'ArchiveController@getTypesDocumentById');
    //  * A10. Guardar un nuevo tipo de archivo
    Route::post('onStoreTypeArchive/', 'ArchiveController@onStoreTypeArchive');
    //  * A11. Guardar los archivos del documento
    Route::post('storeArchivesOfDocument', 'ArchiveController@storeArchivesOfDocument');
    //  * A12. Obtiene la lista de documentos y contenedores que sean menor al actual
    Route::post('getDocumentAndFilesContainerById', 'ArchiveController@getDocumentAndFilesContainerById');
    //  * A13. Obtiene la lista de documentos y contenedores que estan libres para su registro
    Route::post('getDocumentAndContainerFree', 'ArchiveController@getDocumentAndContainerFree');
    //  * A14. Guardar los documentos y contenedores en el contenedor
    Route::post('storeDocumentsAndContainers', 'ArchiveController@storeDocumentsAndContainers');
    //  * A16. Busca los documentos para la solicitud de prestamo
    Route::post('getDataDocument', 'ArchiveController@getDataDocument');
    //  * A17. Obtener una lista de las solicitudes de reserva por usuario.
    Route::post('getBookingDocument', 'ArchiveController@getBookingDocument');
    //  * A18. Guardar la reserva de documentos por el usuario
    Route::post('storeBookingDocument', 'ArchiveController@storeBookingDocument');
    //  * A19. Busca los documentos reservados para la solicitud
    Route::post('getDataBookingDetails', 'ArchiveController@getDataBookingDetails');
    //  * A20. Guarda los documentos digitalizados
    Route::post('storeDigitalDocument', 'ArchiveController@storeDigitalDocument');
    //  * A21. Obtiene el documento digital seleccionado
    Route::get('getDigitalDocumentById', 'ArchiveController@getDigitalDocumentById');
    Route::get('getDigitalDocumentById2', 'ArchiveController@getDigitalDocumentById2');
    //  * A22. Guarda las tranferencias realizadas entre dos contenedores
    Route::post('storeTransferDocumentsAndContainers', 'ArchiveController@storeTransferDocumentsAndContainers');
    //  * A23. Realizar la entrega, devolucion o cancelacion de la reserva
    Route::post('storeChangeStateReservation', 'ArchiveController@storeChangeStateReservation');
    //  * A24. Muestra el reporte del documento
    Route::get('getReportDocument/', 'ArchiveController@getReportDocument');
    //  * A25. Quita el enlace al contenedor
    Route::post('removeLinkToContainer', 'ArchiveController@removeLinkToContainer');
    //  * A26. Muestra el reporte de la reserva
    Route::get('getReportBooking/', 'ArchiveController@getReportBooking');
    //  * A27. Muestra el reporte de la reserva
    Route::get('getReportBorrowed/', 'ArchiveController@getReportBorrowed');
    //  * A28. Muestra el reporte del contenedor y los documentos que contiene
    Route::get('getReportDocumentsAndContainers/', 'ArchiveController@getReportDocumentsAndContainers');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Almacenes
    //  |--------------------------------------------------------------------------
    //  * M1. Obtiene la lista de materiales con una breve descripcion
    Route::post('material', 'StoreController@getMaterialsByDescription');
    //  * M2. Obtiene la lista de movimientos del material
    Route::post('getMovementOfMaterial', 'StoreController@getMovementOfMaterial');
    //  * M3. Reporte para mostrar el kardex de un material
    Route::get('ReportKardexByMaterial/', 'StoreController@getReportKardexByMaterial');

    Route::post('addMaterial', 'StoreController@storeMaterial');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el sistema de Presupuestos Individuales
    //  |--------------------------------------------------------------------------
    //  * 1. Obtener una lista de presupuestos individuales por usuario de el recurso utilizado.
    Route::post('singlebudget/', 'SingleBudgetController@getSingleBudget');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Solvencias
    //  |--------------------------------------------------------------------------
    //  * SO1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
    Route::post('getDebtorsDocument', 'SolvencyController@getDebtorsDocument');
    //  * SO2. Agregar un nuevo documento de deudor
    Route::post('storeDebtorDocument', 'SolvencyController@storeDebtorDocument');
    //  * SO3. Obtiene la informacion necesario del recurso solicitado por su id
    Route::post('getDocumentDetails', 'SolvencyController@getDocumentDetails');
    // *  SO4. Guarda los documentos digitalizados de las deudas
    Route::post('storeDigitalDocumentSolvency', 'SolvencyController@storeDigitalDocument');
    // * SO5. Obtiene los documentos digitalizados de las deudas
    Route::get('getDigitalDocumentSolvency', 'SolvencyController@getDigitalDocument');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Granjas
    //  |--------------------------------------------------------------------------
    //  * G1. Obtiene la lista de los dias de venta de los productos de la granja
    Route::post('getFarmSaleDays', 'FarmController@getFarmSaleDays');
    //  * G2. Agregar un nuevo dia de venta de los productos de la granja
    Route::post('storeFarmSaleDays', 'FarmController@storeFarmSaleDays');
    //  * G3. Agregar ventas al dia de venta de los productos de la granja
    Route::post('storeCustomerSaleDetail', 'FarmController@storeCustomerSaleDetail');
    //  * G4. Obtener el numero de comprobante para la venta de productos
    Route::post('getVoucherNumber', 'FarmController@getVoucherNumber');
    //  * G5. Obtiene un dia de venta de los productos de la granja
    Route::post('getFarmSaleDayById', 'FarmController@getFarmSaleDayById');
    //  * G6. Obtiene un producto a traves de su codigo
    Route::post('getProductForSale', 'FarmController@getProductForSale');
    //  * G7. Obtiene el numero de comprobante para la venta actual
    Route::post('getCurrentVoucherNumber', 'FarmController@getCurrentVoucherNumber');
    //  * G8. Imprimir el reporte de la venta actual.
    Route::get('customerSaleDetailReport/', 'FarmController@customerSaleDetailReport');
    //  * G9. obtener todas las ventas correspondientes a un dia en especifico
    Route::post('getFarmSaleDetailById', 'FarmController@getFarmSaleDetailById');
    //  * G10. Imprimir el reporte de ventas del dia.
    Route::get('customerSaleDetailDayReport/', 'FarmController@customerSaleDetailDayReport');
    //  * G11. Cerrar el reporte de ventas del dia.
    Route::post('setCloseSaleDetailDay', 'FarmController@setCloseSaleDetailDay');
    //  * G12. Anular la transaccion de una venta erronea.
    Route::post('updateCancelTransaction', 'FarmController@updateCancelTransaction');
    //  * G13. Imprimir el reporte del ingreso actual.
    Route::get('customerIncomeDetailReport/', 'FarmController@customerIncomeDetailReport');
    //  * G14. Obtiene la cantidad de productos registrados
    Route::post('getCurrentProductsById', 'FarmController@getCurrentProductsById');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Memoriales
    //  |--------------------------------------------------------------------------
    //  * ME1. Obtener la lista de memoriales para su verificacion
    Route::post('getRequestsMemorial', 'DocumentController@getRequestsMemorial');
    //  * ME2. Obtiene el memorial por su id y gestion
    Route::post('getRequestMemorialById', 'DocumentController@getRequestMemorialById');
    //  * ME3. Imprimir el memorial seleccionado
    Route::get('reportRequestMemorial', 'DocumentController@reportRequestMemorial');
    //  * EF4. Obtener documentos digitalizados
    Route::get('getDigitalDocumentById/', 'DocumentController@getDigitalDocumentById');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Estados Financieros
    //  |--------------------------------------------------------------------------
    //  * EF1. Obtener la lista de estados financieros
    Route::post('getFinancialStatements', 'DocumentController@getFinancialStatements');
    //  * EF2. Obtener la lista de documentos subidos para cada estado financiero
    Route::post('getDocumentsbyFinalcialStatemet', 'DocumentController@getDocumentsbyFinalcialStatemet');
    //  * EF3. Guarda los documentos digitalizados
    Route::post('storeDigitalDocument', 'DocumentController@storeDigitalDocument');
    //  * EF4. Obtener documentos digitalizados
    Route::get('getDigitalFinancialDocument/', 'DocumentController@getDigitalFinancialDocument');
    //  * EF5. Elimina un documento mal subido
    Route::post('setDeleteDigitalDocument', 'DocumentController@setDeleteDigitalDocument');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Recursos Propios
    //  |--------------------------------------------------------------------------
    //  * RP1. Obtener la lista de cursos de posgrado.
    Route::post('courses', 'ResourceController@getCoursesOfPostgraduate');
    //  * RP2. Guardar un curso de postgrado.
    Route::post('storeCourseOfPostgraduate', 'ResourceController@storeCourseOfPostgraduate');
    //  * RP3. Obtiene las transacciones realizadas en caja universitaria del valorado - curso de postgrado
    Route::post('getInputCourse', 'ResourceController@getInputCourse');
    //  * RP4. Guarda las transacciones conciliadas del curso de postgrado
    Route::post('storeInputCourse', 'ResourceController@storeInputCourse');
    //  * RP5. obtiene los ingresos  conciliados del curso de postgrado
    Route::post('getInputTransactionsOfCourse', 'ResourceController@getInputTransactionsOfCourse');
    //  * RP10. Obtener la lista de programas academicos 
    Route::post('getPrograms', 'ResourceController@getPrograms');
    //  * RP11. Obtener la lista de programas academicos 
    Route::post('getTypesOfProgram', 'ResourceController@getTypesOfProgram');
    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Elecciones
    //  |--------------------------------------------------------------------------
    //  * E1 . Obtener la persona por su carnet de identidad y la mesa donde deberia realizar su boto
    Route::post('getAuthorizedPerson', 'ElectionController@getAuthorizedPerson');
    //  * E2 . Obtener la persona por su carnet de identidad y si se encuentra habilitado
    Route::post('getTableElection', 'ElectionController@getTableElection');
    //  * E3 . Obtener la lista de mesas habilitadas para la eleccion
    Route::post('getInformationTablets', 'ElectionController@getInformationTablets');
    //  * E4 . Obtener la lista de candidatos habilitados para la eleccion
    Route::post('getInformationCandidates', 'ElectionController@getInformationCandidates');
    //  * E5 . Guardar las votaciones por candidatos y mesa
    Route::post('storeVotesForCandidates', 'ElectionController@storeVotesForCandidates');
    //  * E6 . Muestra el reporte de los datos por mesa
    Route::get('getReportTablet/', 'ElectionController@getReportTablet');
    Route::get('getReportTablet2/', 'ElectionController@getReportTablet2');
    //  * E7 . Muestra el reporte general de los datos por mesa
    Route::get('getReportGeneralTablet/', 'ElectionController@getReportGeneralTablet');
    Route::get('getReportGeneralTablet2/', 'ElectionController@getReportGeneralTablet2');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para los componentes desarrollados en le frontend
    //  |--------------------------------------------------------------------------
    //  * COM1. Obtener una lista de personas utilizando una descripcion del recurso utilizado.
    Route::post('getPersonsByDescriptionWithPagination', 'GeneralController@getPersonsByDescriptionWithPagination');
    //  * COM2. Obtiene una lista de categorias programaticas que coinciden con la descripcion.
    Route::post('getProgramCategoryDescriptionWithPagination', 'GeneralController@getProgramCategoryDescriptionWithPagination');
    //  * COM3. Obtiene una lista de valores universitarios que coinciden con la descripcion.
    Route::post('getUniversityValuesDescriptionWithPagination', 'GeneralController@getUniversityValuesDescriptionWithPagination');

});
