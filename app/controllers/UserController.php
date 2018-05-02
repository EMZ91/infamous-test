<?php

class UserController extends BaseController {

    /**
     * Show the profile for the given user.
     */
    public function index() {
        $users = User::all();
        return View::make('user.index', array('users' => $users));
    }

    public function save() {
        //check if its our form
        if (Session::token() !== Input::get('_token')) {
            return Response::json(array(
                        'msg' => 'Unauthorized attempt to save user'
            ));
        }


        $validator = Validator::make(
                        array(
                    'name' => Input::get('name'),
                    'email' => Input::get('email'),
                    'date_of_birth' => Input::get('date_of_birth'),
                        ), array(
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'date_of_birth' => 'date|date_format:Y-m-d',
                        )
        );
        $id = Input::get('id');
        if ($id)
            $validator[0]['email'] .= ',email,' . $id;
        if ($validator->fails()) {
            $response = array(
                'status' => 'fails',
                'msg' => $validator->messages(),
            );
        } else {
            $user = new User;

            $user->name = Input::get('name');
            $user->phone = Input::get('phone');
            $user->email = Input::get('email');
            $user->gender = Input::get('gender');
            $user->date_of_birth = Input::get('date_of_birth');
            $user->biography = Input::get('biography');
            $file = Input::file('profile_picture');
            $destinationPath = 'upload/';
            $filename = $file->getClientOriginalName();
            $filename = time() . '_' . $filename;
            Input::file('profile_picture')->move($destinationPath, $filename);
            $user->profile_picture = $filename;
            $user->save();

            $response = array(
                'status' => 'success',
                'msg' => 'User saved successfully',
            );
        }

        return Response::json($response);
    }

}
