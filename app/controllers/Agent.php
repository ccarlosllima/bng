<?php
namespace bng\Controllers;

use bng\Controllers\BaseController;
use bng\Models\Agents;

class Agent extends BaseController
{
    public function my_clients()
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location:index.php');
        }
        
        // get all agent clients
        $id_agent = $_SESSION['user']->id;
        $model = new Agents();
        $results = $model->get_agent_clients($id_agent);

        $data['user'] = $_SESSION['user'];
        $data['client'] = $results['data'];
    
        $this->view('layouts/html_header');
        $this->view('navbar',$data);
        $this->view('agent_clients', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }
}