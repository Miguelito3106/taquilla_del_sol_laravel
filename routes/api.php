<?php

use App\Http\Controllers\AdministradoresController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AsientosController;
use App\Http\Controllers\AsientosEventosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodigoVerificacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



//** ----- REGISTRAR USUARIO ----- */

Route::post("registrar/cliente", [ClientesController::class, "store"]);

//** ----- LOGIN ----- */
Route::post("login/empresa", [EmpresasController::class, "login"]);
Route::post("login/cliente", [ClientesController::class, "login"]);
Route::post("login/admin", [AdministradoresController::class, "login"]);
Route::post('envio/codigo/verificacion', [CodigoVerificacionController::class, 'enviarCodigo']);
Route::post('verificar/codigo', [CodigoVerificacionController::class, 'verificarCodigo']);
Route::post("olvide/clave/cliente", [ClientesController::class, "olvideMiClave"]);
Route::post("olvide/clave/empresa", [EmpresasController::class, "olvideMiClaveEmpresa"]);
Route::post("olvide/clave/admin", [AdministradoresController::class, "olvideMiClaveAdmin"]);




Route::middleware(["auth:sanctum"])->group(function () {
    //Acesso Clientes
    Route::middleware("abilities:Cliente")->group(function () {
        Route::get("me/cliente", [ClientesController::class, "me"]);
        Route::post("logout/cliente", [ClientesController::class, "logout"]);
        Route::put("actualizarCliente/{id}", [ClientesController::class, "updateCliente"]);
        Route::put("cambiar/clave/cliente/{id}", [ClientesController::class, "cambiarClave"]);
        Route::put("cambiar/correo/cliente/{id}", [ClientesController::class, "cambiarCorreo"]);
    });
});


Route::middleware(["auth:sanctum"])->group(function () {
    //Acesso Clientes
    Route::middleware("abilities:Empresa")->group(function () {
        Route::get("me/Empresa", [EmpresasController::class, "me"]);
        Route::post("logout/empresa", [EmpresasController::class, "logout"]);
    });
});

Route::middleware(["auth:sanctum"])->group(function () {
    //Acesso Clientes
    Route::middleware("abilities:Admin")->group(function () {
        Route::get("me/administrador", [AdministradoresController::class, "me"]);
        Route::post("logout/admin", [AdministradoresController::class, "logout"]);
    });
});

//Clientes

Route::get("listarClientes", [ClientesController::class, "index"]);
Route::get("verCliente/{id}", [ClientesController::class, "show"]);
Route::delete("eliminarCliente/{id}", [ClientesController::class, "destroy"]);
Route::put("cambiarClave/{id}", [ClientesController::class, "cambiarClave"]);

//Administradores
Route::get("listarAdministradores", [AdministradoresController::class, "index"]);
Route::post("registrarAdministradores", [AdministradoresController::class, "store"]);
Route::put("actualizarAdministradores/{id}", [AdministradoresController::class, "update"]);
Route::put("cambiarClave/{id}", [AdministradoresController::class, "cambiarClave"]);

//Empresas
Route::get("listarEmpresas", [EmpresasController::class, "index"]);
Route::post("registrarEmpresa", [EmpresasController::class, "store"]);
Route::get("empresa/{id}", [EmpresasController::class, "show"]);
Route::put("actualizarEmpresa/{id}", [EmpresasController::class, "update"]);
Route::delete("eliminarEmpresa/{id}", [EmpresasController::class, "destroy"]);
Route::put("cambiarClave/{id}", [EmpresasController::class, "cambioClave"]);

//Categorias
Route::get("listarCategorias", [CategoriasController::class, "index"]);
Route::post("registrarCategoria", [CategoriasController::class, "store"]);
Route::put("actualizarCategoria/{id}", [CategoriasController::class, "update"]);
Route::delete("eliminarCategoria/{id}", [CategoriasController::class, "destroy"]);

//Eventos
Route::get("evento/{id}", [EventosController::class, "evento"]);
Route::get("listarEventos", [EventosController::class, "index"]);
Route::get("eventos/disponibles", [EventosController::class, "eventosDisponibles"]);
Route::post("registrarEventos", [EventosController::class, "store"]);
Route::put("actualizarEventos/{id}", [EventosController::class, "update"]);
Route::delete("eliminarEventos/{id}", [EventosController::class, "destroy"]);
Route::post("cambiar/estado/evento/{id}", [EventosController::class, "cambioDeEstadoDelEvento"]);


//Tickets
Route::get("listarTickets", [TicketController::class, "index"]);
Route::post("registrarTickets", [TicketController::class, "store"]);
Route::put("actualizarTickets/{id}", [TicketController::class, "update"]);
Route::delete("eliminarTickets/{id}", [TicketController::class, "destroy"]);

//Pagos
Route::get("listarPagos", [PagosController::class, "index"]);
Route::post("registrarPagos", [PagosController::class, "store"]);
Route::put("actualizarPagos/{id}", [PagosController::class, "update"]);
Route::delete("eliminarPagos/{id}", [PagosController::class, "destroy"]);

//Asientos
Route::get("asientos/evento/{id}", [AsientosEventosController::class, "asientosPorEventento"]);
Route::get("listarAsientos", [AsientosController::class, "index"]);
Route::post("registrarAsientos", [AsientosController::class, "store"]);
Route::get("mostrarAsiento/{id}", [AsientosController::class, "show"]);
Route::put("actualizarAsientos/{id}", [AsientosController::class, "update"]);
Route::delete("eliminarAsientos/{id}", [AsientosController::class, "destroy"]);
