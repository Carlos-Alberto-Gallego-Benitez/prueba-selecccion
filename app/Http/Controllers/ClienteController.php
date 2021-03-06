<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClienteModel;
use App\Models\ServicioModel;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = ClienteModel::where('Estado', 1) ->get() ; //se ejecuta la consulta en la base de datos (SELECT)
        $action = 'index';
        return view('layouts.clientes', compact('clientes', 'action'));
    }


    public function create() //para retornar la vista de el registro
    {
        $action = 'registro';
        return view('layouts.clientes', compact('action'));
    }

 
    public function store(Request $request) //se guarda el registro de el cliente
    {  
        
        $request->validate([
            'Nombre' => 'required',
            'Imagen' => 'required',
            'Cedula' => 'required',
            'Correo' => 'required',
            'Telefono' => 'required',
            'Observacion' => 'required'
        ]);
        $imagen = $request->file('Imagen')->store('clientes', 'public'); //guardado de la imagen
        $url = Storage::url($imagen);
        
        //$role = ClienteModel::create($request->all());

        ClienteModel::create([
            'Nombre' => $request->Nombre,
            'Imagen' => $imagen,
            'Cedula' => $request->Cedula,
            'Correo' => $request->Correo,
            'Telefono' => $request->Telefono,
            'Observacion' => $request->Observacion
        ]);

        $clientes = ClienteModel::where('Estado', 1) ->get(); //se ejecuta la consulta en la base de datos (SELECT) para listar los clientes
        $action = 'index';
        return view('layouts.clientes', compact('clientes', 'action'))->with('info', 'ok');
    }


    public function show(ClienteModel $cliente) //cargar los datos de la acción ver
    {
        $servicio = ServicioModel::where('idcliente', $cliente->id) ->get(); 
        $action = 'ver';
        return view('layouts.clientes', compact('cliente','servicio', 'action'));
    }


    public function edit(ClienteModel $cliente) //cargar los datos de el cliente a editar
    {
        $action = 'editar';
        return view('layouts.clientes', compact('cliente', 'action'));
    }


    public function update(Request $request,  $cliente) //se guardan los datos de el cliente a editar
    {
        $action = 'index';
        if($request->Imagen){
            $request->validate([
                'Nombre' => 'required',
                'Imagen' => 'required',
                'Cedula' => 'required',
                'Correo' => 'required',
                'Telefono' => 'required',
                'Observacion' => 'required'
            ]);
            $usuarios =  ClienteModel :: find($cliente);

            $usuarios->Nombre = $request->Nombre;
            $usuarios->Imagen = $request->Imagen;
            $usuarios->Cedula = $request->Cedula;
            $usuarios->Correo = $request->Correo;
            $usuarios->Telefono = $request->Telefono;
            $usuarios->Observacion = $request->Observacion;
    
            $usuarios->save();
        }
        else{
            $request->validate([
                'Nombre' => 'required',
                'Cedula' => 'required',
                'Correo' => 'required',
                'Telefono' => 'required',
                'Observacion' => 'required'
            ]);
            $usuarios =  ClienteModel :: find($cliente);

            $usuarios->Nombre = $request->Nombre;
            $usuarios->Cedula = $request->Cedula;
            $usuarios->Correo = $request->Correo;
            $usuarios->Telefono = $request->Telefono;
            $usuarios->Observacion = $request->Observacion;
    
            $usuarios->save();
        }
 
        //$role = ClienteModel::update($request->all());




        $clientes = ClienteModel::where('Estado', 1) ->get(); //se ejecuta la consulta en la base de datos (SELECT) para listar los clientes
        $action = 'index';
        return view('layouts.clientes', compact('clientes', 'action'))->with('info', 'ok');
    }


    public function destroy($id)
    {
        //
    }

    public function delete(ClienteModel $cliente)
    {
        if ($cliente->Estado == 1) {
            $cliente->update(['Estado' => 0]);
        }

        $clientes = ClienteModel::where('Estado', 1) ->get(); //se ejecuta la consulta en la base de datos (SELECT) para listar los clientes
        $action = 'index';
        return view('layouts.clientes', compact('clientes', 'action'))->with('info', 'ok'); 
  
    }
}
