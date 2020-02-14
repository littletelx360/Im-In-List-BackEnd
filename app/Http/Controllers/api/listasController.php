<?php

namespace App\Http\Controllers\api;

use App\Listas;
use Illuminate\Http\Request;

/**
 *  - Extiende de controlador padre
 */
class listasController extends protectedUserController
{

    /**
     *  - GETLISTAS
     * - Devuelve las listas que ha creado el usuario -> user
     */
    public function getLista()
    {

        //array_push($listas, $this->user->listas()->get());
        $listas = $this->user->listas()->get()->toArray();

        return $listas;

    }

    /**
     *  - INFOLISTA
     * - Informacion de una lista si la ha creado el usuario -> user
     */
    public function infoListaAdmin($id)
    {
        $lista = Listas::find($id);

        if (!$id || !$lista || !$this->user->listas()->find($id)) {
            return response()->json([
                'message' => 'Error lista con el ' . $id . ' no se ha encontrado',
            ], 400);
        }

        return $lista;
    }

    /**
     *  - ADDLISTA
     * - Agrega una lista ->user
     */
    public function addLista(Request $request)
    {

        // - usuario/personalida /P
        // - usuario/aleatorio   /R
        // - /aleatorio         /0

        $lista = new Listas();

        if ($this->user->role == 0 || $this->user->role == 3) {
            $lista->url = $this->user->name . '_' . $request->url;

        } else if ($this->user->role == 1) {
            $lista->url = $this->user->name . '_' . $this->random();

        } else {
            $lista->url = '_' . $this->random();
        }

        $lista->titulo = $request->titulo;
        $lista->descripcion = $request->descripcion;

        if ($this->user->role == 0 || $this->user->role == 3) {
            $lista->passwordLista = $request->passwordLista;
        } else {
            $lista->passwordLista = null;
        }

        $lista->elementos = json_encode($request->elementos);

        if ($this->user->listas()->save($lista)) {
            return response()->json([
                'message' => 'Lista creada correctamente',
                'lista' => $lista,
            ]);
        } else {
            return response()->json([
                'message' => 'Error la lista no se ha creado',
            ], 500);
        }

    }

    public function random()
    {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 20; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     *  - EDITLISTA
     * - Edita una lista por id -> user
     */
    public function editLista($id, Request $request)
    {
        $lista = $this->user->listas()->find($id);

        // - En caso de id erronea
        if (!$id || !$lista) {
            return response()->json([
                'message' => 'Error la lista con el ' . $id . ' no se ha modificado o encontrado',
            ], 400);
        }

        $updated = $lista->fill($request->all())->save();
        if ($updated) {
            return response()->json([
                'message' => 'Lista modificada correctamente',
                'lista' => $lista,
            ]);
        } else {
            return response()->json([
                'message' => 'Error la lista no se ha creado',
            ], 500);
        }
    }

    /**
     *  - DElLIST
     * - Elimina una lista por el id si la tiene el user -> user
     */
    public function delList($id)
    {
        $lista = $this->user->listas()->find($id);

        if (!$lista || !$id) {
            return response()->json([
                'message' => 'Error la lista no se ha encontrado',
            ], 400);
        }

        if ($lista->delete()) {
            return response()->json([
                'message' => 'Lista eliminada correctamente',
            ]);
        } else {
            return response()->json([
                'message' => 'Error la lista no se ha eliminado',
            ], 500);
        }
    }
}
