<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Requests\CriarEstudante;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();

        return $students->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEstudante $request)
    {   
        if(!Storage::exists('localFiles/')) {
            Storage::makeDirectory('localFiles/', 0775, true);
            }
        $boletim = base64_decode($request->foto);
        $boletimName = uniqid() . '.pdf';
        $path = storage_path('/app/localFiles/' . $boletimName);
        file_put_contents($path,$boletim);
        
        $newStudent = new Student;

        $newStudent->nome = $request->nome;
        $newStudent->idade = $request->idade;
        $newStudent->email = $request->email;
        $newStudent->cpf = $request->cpf;
        $newStudent->telefone = $request->telefone;
        $newStudent->boletim = $boletimName;

        $newStudent->save();
        return response()->json($newStudent);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        if($request->nome){
          $student->nome = $request->nome;
        }
        if($request->idade){
          $student->idade = $request->idade;
        }
        if($request->email){
          $student->email = $request->email;
        }
        if($request->cpf){
          $student->cpf = $request->cpf;
        }
        if($request->telefone){
          $student->telefone = $request->telefone;
        }

        $student->save();
        return response()->json('Cliente atualizado com sucesso\n'. $student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        Storage::delete('localFiles/' . $student->boletim);
        Student::destroy($student->id);
        return response()->json('Estudante deletado com sucesso');
    }
}
