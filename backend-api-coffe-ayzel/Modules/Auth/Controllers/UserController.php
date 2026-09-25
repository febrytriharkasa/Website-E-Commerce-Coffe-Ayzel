<?php

namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use Modules\Auth\Models\Users;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    public function index()
    {
        //
        return view('Modules\Auth\Views\login');
    }

    public function loginProcess()
    {
        $sesion = session();
        $userModel = new Users();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $dataUser = $userModel->where('email', $email)->first();

        if ($dataUser) {
            $verify_pass = password_verify($password, $dataUser['password']);

            if ($verify_pass) {
                $sesData = [
                    'id' => $dataUser['id'],
                    'nama' => $dataUser['nama'],
                    'email' => $dataUser['email'],
                    'password' => $dataUser['password'],
                    'logged_in' => true
                ];
                $sesion->set($sesData);

                return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $dataUser['nama']);
            } else {
                return redirect()->back()->with('error', 'Password yang Anda masukkan salah!');
            }
        } else {
            return redirect()->back()->with('error', 'Email yang Anda masukkan salah!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda berhasil logout');
    }
}