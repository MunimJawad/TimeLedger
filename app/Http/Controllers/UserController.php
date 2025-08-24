<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //For Admin 
    public function index(){
        $users=User::withTrashed()->get();
        return view('admin.users',compact('users'));
    }

    public function edit(User $user){
      
        return view('admin.edit',compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|string',
            'password'=>'required|min:8'
        ]);

        $user->update($request->only('name', 'email', 'password', 'role'));

        return redirect()->route('admin.user_list')->with('success', 'User updated successfully');
    }

    public function destroyUser(Request $request, User $user){

        $user->delete();
        return redirect()->route('admin.user_list')->with('success','User deleted successfully');
    }

    public function restore($id){
         $user=User::withTrashed()->findOrFail($id);
         $user->restore();
         return redirect()->back()->with('success', 'User restored successfully.');
    }
  

    //For Users

    public function profile(){
        $user=Auth::user();
        return view('profile.profile',compact('user'));
    }

    public function profileEditForm(){
        $user=Auth::user();
        return view('profile.profile_edit',compact('user'));
    }

    public function updateProfile(Request $request){
       $user = $request->user();

       $request->validate([
        'name'=>'required|string|max:255',
        'email'=>'required|email|unique:users,email',
        'password'=>'required|min:8|nullable'
       ]);

       $user->name=$request->name;
       $user->email=$request->email;

       if($request->password){
        $user->password=Hash::make($request->password);
       }

       $user->save();

       return redirect()->route('profile')->with('success', 'Profile updated successfully');
       

    }
 
}
