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

        $validation_data = array(
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'date_of_birth' => Input::get('date_of_birth'),
        );
        $validation_array = array(
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'date_of_birth' => 'date|date_format:Y-m-d',
        );
        if ($id) {
            $validation_array['email'] .= ',email,' . $id;
        }
        $validator = Validator::make($validation_data, $validation_array);
        if ($validator->fails()) {
            $response = array(
                'status' => 'fails',
                'msg' => $validator->messages(),
            );
        } else {
            $user_array = array();
            if ($id) {
                $user = User::find($id);
                $user_array['profile_picture'] = $user->profile_picture;
            } else {
                $user = new User;
                $user_array['profile_picture'] = '';
            }
            $user_array['name'] = $user->name = Input::get('name');
            $user_array['phone'] = $user->phone = Input::get('phone');
            $user_array['email'] = $user->email = Input::get('email');
            $user_array['gender'] = $user->gender = Input::get('gender');
            $user_array['date_of_birth'] = $user->date_of_birth = Input::get('date_of_birth');
            $user_array['biography'] = $user->biography = Input::get('biography');
            $file = Input::file('profile_picture');
            $destinationPath = 'upload/';
            if ($file) {
                $filename = $file->getClientOriginalName();
                $filename = time() . '_' . $filename;
                Input::file('profile_picture')->move($destinationPath, $filename);
                $user_array['profile_picture'] = $user->profile_picture = $filename;
            }
            $user->save();
            $user_array['id'] = $user->id;

            $response = array(
                'status' => 'success',
                'msg' => 'User saved successfully',
                'user' => $user_array
            );
        }

        return Response::json($response);
    }

}
