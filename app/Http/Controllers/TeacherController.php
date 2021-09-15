<?php

namespace App\Http\Controllers;
use App\User;
use App\Http\Resources\TeacherResource;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  TeacherResource::collection(Teacher::all());
    }

    function waitingApproval(){
		return User::where('role','teacher')->where('activated','0')->orderBy('id','DESC')->get();
	}

	function approveOne($id){
		$user = User::find($id);
		$user->activated = 1;
		$user->save();
		return $this->waitingApproval();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('originalservice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::where('username',trim($request->get('username')))->count() > 0){
            return response()->json(['error', 'Sorry this username have been chosen by another user'], 200);
		}

        if(User::where('email',$request->get('username'))->count() > 0){
            return response()->json(['error', 'Sorry this email have been chosen by another user'], 200);
		}

        

        $request->validate([
            'username' =>'required',
            'email' =>'required',
            'fullName' =>'required',
            'password'  => 'required',
            'role' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'phoneNo' => 'required',
            'mobileNo' => 'required',
            'transport' => 'required',
            'birthday' => 'required'

        ]);


        $User = new User();
		$User->username = $request->get('username');
		$User->email = $request->get('email');
		$User->fullName = $request->get('fullName');
		$User->password = Hash::make($request->get('password'));
		$User->role = "teacher";
		$User->gender = $request->get('gender');
		$User->address = $request->get('address');
		$User->phoneNo = $request->get('phoneNo');
		$User->mobileNo = $request->get('mobileNo');
		$User->transport = $request->get('transport');
		if($request->get('birthday') != ""){
			$birthday = explode("/", $request->get('birthday'));
			$birthday = mktime(0,0,0,$birthday['0'],$birthday['1'],$birthday['2']);
			$User->birthday = $birthday;
		}
		
		
		if (Input::hasFile('photo')) {
			$fileInstance = Input::file('photo');
			$newFileName = "profile_".$User->id.".".$fileInstance->getClientOriginalExtension();
			$file = $fileInstance->move('uploads/profile/',$newFileName);

			$User->photo = "profile_".$User->id.".".$fileInstance->getClientOriginalExtension();
			
		}
        
        $User->save();
       

       
        
        
        
       

        return response()->json(['success', 'Teachers created!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        
        return response()->json(['user'  => $user ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(User::where('username',trim($request->get('username')))->count() > 0){
            return response()->json(['error', 'Sorry this username have been chosen by another user'], 200);
		}

        if(User::where('email',$request->get('username'))->count() > 0){
            return response()->json(['error', 'Sorry this email have been chosen by another user'], 200);
		}

        

        $request->validate([
            'username' =>'required',
            'email' =>'required',
            'fullName' =>'required',
            'password'  => 'required',
            'role' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'phoneNo' => 'required',
            'mobileNo' => 'required',
            'transport' => 'required',
            'birthday' => 'required'

        ]);
		$User = User::find($id);
		$User->username = $request->get('username');
		$User->email = $request->get('email');
		$User->fullName = $request->get('fullName');
		$User->password = Hash::make($request->get('password'));
		$User->role = "teacher";
		$User->gender = $request->get('gender');
		$User->address = $request->get('address');
		$User->phoneNo = $request->get('phoneNo');
		$User->mobileNo = $request->get('mobileNo');
		$User->transport = $request->get('transport');
		if($request->get('birthday') != ""){
			$birthday = explode("/", $request->get('birthday'));
			$birthday = mktime(0,0,0,$birthday['0'],$birthday['1'],$birthday['2']);
			$User->birthday = $birthday;
		}
		
		
		if (Input::hasFile('photo')) {
			$fileInstance = Input::file('photo');
			$newFileName = "profile_".$User->id.".".$fileInstance->getClientOriginalExtension();
			$file = $fileInstance->move('uploads/profile/',$newFileName);

			$User->photo = "profile_".$User->id.".".$fileInstance->getClientOriginalExtension();
			
		}

        $User->save();
       
       

        return response()->json(['success', 'Teachers updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::delete('delete from original_services where id = ?',[$id]);
        return redirect('/originalservices')->with('success', 'Original Service  deleted!');
    }
}
