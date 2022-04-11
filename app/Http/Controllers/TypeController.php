<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Article;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;

use Illuminate\Http\Request;
use Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::sortable()->get();
        return view("type.index", ['types'=>$types]);
    }


    public function indexAjax() {

        $types = Type::sortable()->get();
        $types_array = array(
            'types' => $types );

        $json_response =response()->json($types_array); 
     
        return $json_response;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("type.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $input = [
            'type_title'=> $request->type_title,
            'type_description'=> $request->type_description,
        ];

        $rules = [
            'type_title'=> 'required|min:3', 
            'type_description'=> 'required',
        ];

        $customMessages = [
            'required' => "This field is required"
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {

            $errors = $validator->messages()->get('*'); 
            $type_array = array(
                'errorMessage' => "validator fails",
                'errors' => $errors
            );
        } else {
        
        $type = new Type;
        $type->title = $request->type_title;
        $type->description = $request->type_description;
        $type->save();

        $sort = $request->sort ;
        $direction = $request->direction ;

        $types = Type::with("typeArticle")->sortable([$sort => $direction ])->get();

        $type_array = array(
            'successMessage' => "Type stored succesfuly",
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
            'types' => $types,
        );
    }
        $json_response =response()->json($type_array); 
       
        return $json_response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        $type_array = array(
            'successMessage' => "Type retrieved succesfuly",
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
        );

        $json_response =response()->json($type_array); 

        return $json_response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTypeRequest  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $type->title = $request->type_title;
        $type->description = $request->type_description;
    
        $type->save();

        $type_array = array(
            'successMessage' => "Type updated succesfuly",
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
        );

        // 
        $json_response =response()->json($type_array); 

        return $json_response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $articles_count = count($type->typeArticle);
        
        if($articles_count > 0) {
            $response_array = array(
                'errorMessage' => "Type cannot be deleted because it has articles". $type->id,
            );  
        } else {
            $type->delete();
            $response_array = array(
                'successMessage' => "Type deleted successfuly". $type->id,
            );
        }
 
        $json_response =response()->json($response_array);

        return $json_response;
    }

    public function search(Request $request) {

        $searchValue = $request->searchValue;

        $types = Type::query()
        ->where('title', 'like', "%{$searchValue}%")
        ->orWhere('description', 'like', "%{$searchValue}%")
        ->get();

        if(count($types) > 0) {
            $types_array = array(
                'types' => $types
            );
        } else {
            $types_array = array(
                'errorMessage' => 'No types found'
            );
        }

        $json_response =response()->json($types_array);

        return $json_response;

    }
}
