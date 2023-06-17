<?php
namespace bng\Controllers;

use bng\Controllers\BaseController;
use bng\Models\Agents;

class Agent extends BaseController
{
    // =============================================
    public function my_clients()
    {
        session_start();
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location:index.php');
        }

        // get all agent clients

        $id_agent = $_SESSION['user']->id;

        $model = new Agents();
        $results = $model->get_agent_clients($id_agent);

        $data['user'] = $_SESSION['user'];
        $data['clients'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('agent_clients', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }
    // =============================================
    public function new_client_frm()
    {
        session_start();

        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location:index.php');
        }

        $data['user'] = $_SESSION['user'];
        $data['flatpickr'] = true;

        // check if there are validation errors
        if (!empty($_SESSION['validation_errors'])) {
            $data['validation_errors'] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }
        // check if there is a server error
        if (!empty($_SESSION['server_error'])) {
            $data['server_error'] = $_SESSION['server_error'];
            unset($_SESSION['server_error']);
        }

        $this->view('layouts/html_header', $data);
        $this->view('navbar', $data);
        $this->view('insert_client_frm', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }


    // =============================================
    public function new_client_submit()
    {
        session_start();

        if (!check_session() || $_SESSION['user']->profile != 'agent' || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location:index.php');
        }

        // form validate
        $validation_errors = [];

        // text_name
        if (empty($_POST['text_name'])) {
            $validation_errors[] = 'Nome é de preenchimeto obrigatório.';
        } else {

            if (strlen($_POST['text_name']) < 3 || strlen($_POST['text_name']) > 50) {
                dd(strlen($_POST['text_name']));
                $validation_errors[] = 'O nome deve conter entre 3 e 50 caracteres.';
            }
        }
        // gender
        if (empty($_POST['radio_gender'])) {
            $validation_errors[] = "É obrigatório definir o genero";
        }

        // text_birthdate
        if (empty($_POST['text_birthdate'])) {
            // check if birthdate is valid and is older than today
            $birthdate = \DateTime::createFromFormat('d-m-Y', $_POST['text_birthdate']);
            if (!$birthdate) {
                $validation_errors[] = "A data de nascimento não esta no formato correto.";
            } else {
                $today = new \DateTime();
                if ($birthdate >= $today) {
                    $validation_errors[] = "A data de nascimento tem que ser anterior ao dia atual.";
                }
            }
        }

        // email
        if (empty($_POST['text_email'])) {
            $validation_errors[] = 'E-mail é de preenchimento obrigatório';
        } else {
            if (!filter_var($_POST['text_email'], FILTER_VALIDATE_EMAIL)) {
                $validation_errors[] = 'Email não é valido';
            }
        }

        // phone
        if (empty($_POST['text_phone'])) {
            $validation_errors[] = 'Telefone é de preenchimento obrigatório.';
        } else {
            if (!preg_match("/^9{1}\d{8}$/", $_POST['text_phone'])) {
                $validation_errors[] = 'O telefone deve começar com 9 e ter 9 algarismos no total';
            }
        }
        // check if there are validation error to return to the form
        if (!empty($validation_errors)) {
            $_SESSION['validation_errors'] = $validation_errors;
            $this->new_client_frm();
            return;
        }

        // check if the client already exists with the same nome
        $model = new Agents();
        $results = $model->check_if_client_exists($_POST);
        if ($results['status']) {
            // a person with the same name exists for this agent. Return a serve error
            $_SESSION['server_error'] = "Já existe um cliente com esse nome.";
            $this->new_client_frm();
            return;
        }
        // add new client to the database
        $model->add_new_client_to_database($_POST);

        // return to the main clients page
        $this->my_clients();
    }


    // =============================================
    public function edit_client($id)
    {
        session_start();

        echo aes_decrypt($id);
    }

    // =============================================
    public function delete_client($id)
    {
        session_start();

        echo "deleted ". aes_decrypt($id) ;
    }

}