<?php

namespace App\Http\Controllers;

use App\Models\Asientos;
use App\Models\asientosEventos;
use App\Models\Eventos;
use App\Models\preciosEvento;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class EventosController extends Controller
{
    public function index()
    {
        $eventos = Eventos::all();

        if (!$eventos || $eventos->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No hay eventos vigentes"
            ]);
        }

        return response()->json([
            "success" => true,
            "eventos" =>  $eventos
        ], 200);
    }


    //listar datos de un solo evento id
    public function evento(string $id)
    {
        $evento = Eventos::with('categoria', 'empresa')->find($id);

        if (!$evento) {
            return response()->json([
                "success" => false,
                "message" => "Evento no encontrado"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "evento" =>  $evento
        ], 200);

    }

    public function cambioDeEstadoDelEvento(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            "estado" => "required|in:activo,pendiente,cancelado,finalizado"
        ]);

        if ($validator->fails()) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Error de validaciones en el servidor.",
                "error" => $validator->errors()
            ], 400);
        }

        try {
            $evento = Eventos::find($id);
            if (!$evento) {
                DB::rollBack();
                return response()->json([
                    "success" => false,
                    'message' => 'Evento no encontrado'
                ], 400);
            }



            if ($request->estado === "activo") {
                $precio_id = 0;
                for ($i = 1; $i <= 270; $i++) {
                    $asiento = Asientos::find($i);
                    switch ($asiento->ubicacion_id) {
                        case 1:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 1)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;
                        case 2:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 2)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;
                        case 3:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 3)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;
                        case 4:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 4)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;
                        case 5:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 5)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;

                        case 6:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 6)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;

                        case 7:
                            $precioEvento = preciosEvento::where("evento_id", $id)
                                ->where("ubicacion_id", 7)
                                ->first();
                            if (!$precioEvento) {
                                DB::rollBack();
                                return response()->json([
                                    "success" => false,
                                    "message" => "No se encontró precio para la ubicación ID: {$asiento->ubicacion_id}"
                                ], 400);
                            }
                            $precio_id = $precioEvento->id;
                            break;

                        default:
                            DB::rollBack();
                            return response()->json([
                                "success" => false,
                                "message" => "Error al crear los asientos para el evento $evento->titulo."
                            ]);
                            break;
                    }

                    asientosEventos::create([
                        "evento_id" => $id,
                        "asiento_id" => $asiento->id,
                        "disponible" => true,
                        "precio_id" => $precio_id
                    ]);
                }
            }
            $evento->update($validator->validated());
            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "El evento $evento->tiitulo se ha aceptado correctamente."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Error al cambiar el estado del evento: " . $e->getMessage()
            ]);
        }
    }

    public function eventosDisponibles()
    {
        $eventos = Eventos::with('categoria')
            ->where('estado', 'activo')
            ->get();


        if (!$eventos || $eventos->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No hay eventos vigentes"
            ]);
        }

        return response()->json([
            "success" => true,
            "eventos" =>  $eventos
        ], 200);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        $validacionParaEvento = Validator::make($request->all(), [
            'titulo'        => 'required|string|max:200',
            'descripcion'   => 'required|string',
            'fecha'         => 'required|date',
            'hora_inicio'   => 'required|string|size:8',
            'hora_final'    => 'required|string|size:8',
            'imagen'        => 'required|image',
            'estado'        => 'required|in:activo,pendiente,cancelado,finalizado',
            'empresa_id'    => 'required|integer|exists:empresas,id',
            'categoria_id'  => 'required|integer|exists:categorias,id',
        ]);

        if ($validacionParaEvento->fails()) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Error de validaciones en el servidor.",
                "error" =>  $validacionParaEvento->errors()
            ], 400);
        }


        $validacionParaPrecios = Validator::make($request->all(), [
            "precioPrimerPiso" => "required|integer",
            "precioSugundoPiso" => "required|integer",
            "precioGeneral" => "required|integer"
        ]);
        if ($validacionParaPrecios->fails()) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Error de validaciones en el servidor.",
                "error" =>  $validacionParaPrecios->errors()
            ], 400);
        }

        if (Eventos::where("fecha", $request->fecha)->exists()) {
            return response()->json([
                "success" => false,
                "message" => "Ya existe un evento registrado en esta fecha."
            ], 400);
        }

        $validator_datos = $validacionParaEvento->validated();
        $imagen_file = $request->file('imagen');

        // 1. Separar el archivo de imagen de los datos para la creación inicial
        if ($imagen_file) {
            unset($validator_datos['imagen']);
        }

        // 2. Crear el evento en la DB (sin la ruta de la imagen)
        $eventos = Eventos::create($validator_datos);
        $idEventoCreado = $eventos->id;
        // 3. Procesar y guardar la imagen dentro de la carpeta 'eventos/{slug}'
        if ($imagen_file) {
            $file = $imagen_file;
            $titulo = $eventos->titulo;

            // Generar la subcarpeta
            $carpeta_evento = Str::slug($titulo);
            $extension = $file->getClientOriginalExtension();

            // Nombre de archivo: [slug]-[id].[ext]
            $nombre_archivo = $carpeta_evento . '-' . $eventos->id . '.' . $extension;

            //Prefijamos la carpeta dinámica con 'eventos/'
            $ruta_relativa = Storage::disk('public')->putFileAs(
                'eventos/' . $carpeta_evento, // DIRECTORIO FINAL: eventos/titanic
                $file,
                $nombre_archivo
            );

            // 4. Actualizar el registro del evento con la ruta pública
            $eventos->imagen = Storage::url($ruta_relativa);
            $eventos->save();
            $eventos->refresh();
        }


        for ($i = 1; $i <= 7; $i++) {
            if ($i === 1) {
                preciosEvento::create([
                    "evento_id" => $idEventoCreado,
                    "ubicacion_id" => $i,
                    "precio" => $request->precioGeneral
                ]);
            } else if ($i === 2 || $i === 3 || $i === 7) {
                preciosEvento::create([
                    "evento_id" => $idEventoCreado,
                    "ubicacion_id" => $i,
                    "precio" => $request->precioPrimerPiso
                ]);
            } else if ($i === 4 || $i === 5 || $i === 6) {
                preciosEvento::create([
                    "evento_id" => $idEventoCreado,
                    "ubicacion_id" => $i,
                    "precio" => $request->precioSugundoPiso
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Solicitud del evento $request->titulo creada correctamente",
            'data' => $eventos
        ], 200);
    }


    public function show(string $id)
    {
        $eventos = Eventos::find($id);
        if (!$eventos) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }
        return response()->json($eventos);
    }
    public function update(Request $request, string $id)
    {
        $eventos = Eventos::find($id);

        if (!$eventos) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo'        => 'string|max:200',
            'descripcion'   => 'nullable|string',
            'fecha'         => 'date',
            'hora_inicio'   => 'string|size:8',
            'hora_final'    => 'string|size:8',
            'imagen'        => 'nullable|image|max:2048',
            'estado'        => 'in:activo,pendiente,cancelado,finalizado',
            'empresa_id'    => 'integer|exists:empresas,id',
            'categoria_id'  => 'integer|exists:categorias,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validator_datos = $validator->validated();
        $imagen_file = $request->file('imagen');

        // Separamos la imagen para procesarla por separado
        if (isset($validator_datos['imagen'])) {
            unset($validator_datos['imagen']);
        }

        // LÓGICA PARA ACTUALIZACIÓN DE IMAGEN
        if ($imagen_file) {

            // 1. ELIMINAR la imagen anterior
            if ($eventos->imagen) {
                $ruta_relativa_a_disco = str_replace('/storage/', '', $eventos->imagen);
                if (Storage::disk('public')->exists($ruta_relativa_a_disco)) {
                    Storage::disk('public')->delete($ruta_relativa_a_disco);
                }
            }

            // 2. Preparar la nueva imagen y carpeta
            $file = $imagen_file;

            // Usar el nuevo título del request, o el título actual del evento si no se cambia
            $titulo = $request->input('titulo', $eventos->titulo);

            // Generar la subcarpeta
            $carpeta_evento = Str::slug($titulo);
            $extension = $file->getClientOriginalExtension();

            // Nombre de archivo: [slug]-[id].[ext]
            $nombre_archivo = $carpeta_evento . '-' . $eventos->id . '.' . $extension;

            //  Prefijamos la carpeta dinámica con 'eventos/'
            $ruta_relativa = Storage::disk('public')->putFileAs(
                'eventos/' . $carpeta_evento, // DIRECTORIO FINAL: eventos/titanic
                $file,
                $nombre_archivo
            );

            // 3. Añadir la nueva URL al array de datos para la actualización
            $validator_datos['imagen'] = Storage::url($ruta_relativa);
        }

        // 4. Actualizar el evento con todos los datos validados y la nueva ruta de imagen
        $eventos->update($validator_datos);

        return response()->json($eventos);
    }

    public function destroy(string $id)
    {
        $eventos = Eventos::find($id);

        if (!$eventos) {
            return response()->json(['message' => 'evento no encontrado'], 404);
        }

        // LÓGICA PARA ELIMINAR LA IMAGEN Y SU CARPETA ASOCIADA
        if ($eventos->imagen) {
            // 1. Extraer la ruta relativa al disco 'public'.
            $ruta_relativa_a_disco = str_replace('/storage/', '', $eventos->imagen);

            // 2. Obtener la ruta del directorio padre.
            $ruta_carpeta = dirname($ruta_relativa_a_disco);

            // 3. Eliminar todo el directorio y su contenido (el archivo de imagen).
            if (Storage::disk('public')->exists($ruta_carpeta)) {
                Storage::disk('public')->deleteDirectory($ruta_carpeta);
            }
        }

        // 4. Eliminar el registro del evento de la base de datos.
        $eventos->delete();

        return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}
