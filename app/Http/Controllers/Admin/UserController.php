<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignCenterRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Center;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::all();

    return view('admin.users.index', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.users.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreUserRequest $request)
  {
    $validated = $request->validated();

    $user = new User($validated);

    $user->forceFill([
      'password' => Hash::make($validated['password']),
      'remember_token' => Str::random(60),
    ]);

    $saved = $user->save();

    if ($saved)
      return 1;

    return response('User Registration Failed!', 500);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    return view('admin.users.show', compact('user'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(User $user)
  {
    return view('admin.users.edit', compact('user'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(StoreUserRequest $request, User $user)
  {
    
    $oldPassword = $user->password;

    $validated = $request->validated();

    $user->fill($validated);

    if ($validated['password']) {
      $user->forceFill([
        'password' => Hash::make($validated['password']),
        'remember_token' => Str::random(60),
      ])->save();

      event(new PasswordReset($request->user));
    }
    else
    {
      $user->password = $oldPassword;
    }

    $saved = $user->update();

    if ($saved)
      return 1;

    return response('User Profile Update Failed!', 500);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  public function assignCenter(User $user)
  {
    $centers = Center::all();
    return view('admin.users.assign-center', compact('user', 'centers'));
  }

  public function assignCenterStore(StoreAssignCenterRequest $request, User $user)
  {
    $validated = $request->validated();

    if ($validated['user_id'] != $user->id)
      abort(401);

    $user->center_id = $validated['center_id'];
    
    $isAssigned = $user->update();

    if ($isAssigned)
      return 1;

    return response('Center Assigning Failed!', 500);
  }
}
