<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tarefas', 'trf');
        
        $this->output
             ->set_header("Access-Control-Allow-Origin: *")
             ->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }

    public function index_get()
    {
        $id = $this->input->get('id');

        if (!empty($id)) {
            $data = $this->trf->get_by_id($id);

            if (!empty($data)) {
                $res = [
                    'error' => false,
                    'message' => 'Success: Data retrieved by ID',
                    'data' => $data,
                ];
            } else {
                $res = [
                    'error' => true,
                    'message' => 'Failed: Data not found',
                ];
            }
        } else {
            $pagenum = $this->input->get('pagenum') ?: 0;
            $pagesize = $this->input->get('pagesize') ?: 10;

            $data = $this->trf->get_all($pagenum, $pagesize);

            if (!empty($data)) {
                $res = [
                    'error' => false,
                    'message' => 'Success: All data retrieved',
                    'data' => $data,
                ];
            } else {
                $res = [
                    'error' => true,
                    'message' => 'Failed: No data found',
                ];
            }
        }

        $this->response($res, REST_Controller::HTTP_OK);
    }

	public function index_post()
	{
		$data = [
			'nome_tarefa' => $this->input->post('nome_tarefa'),
			'descricao' => $this->input->post('descricao'),
			'data' => $this->input->post('data'),
			'hora' => $this->input->post('hora'),
			'status' => $this->input->post('status'),
		];

		$insert = $this->trf->save($data);

		if ($insert) {
			$res = [
				'error' => false,
				'message' => 'Insert success',
				'data' => $data,
			];
			$this->response($res, REST_Controller::HTTP_CREATED); // 201
		} else {
			$res = [
				'error' => true,
				'message' => 'Insert failed',
			];
			$this->response($res, REST_Controller::HTTP_BAD_REQUEST); // 400
		}
	}
	public function index_put()
	{
		$input = json_decode(file_get_contents('php://input'), true);
	
		if (!empty($input['id'])) {
			$id = $input['id'];
			unset($input['id']); 
	
			if (empty($input['nome_tarefa']) || empty($input['data']) || empty($input['hora']) || !isset($input['status'])) {
				$res = [
					'error' => true,
					'message' => 'Invalid data: All fields are required',
				];
				$this->response($res, REST_Controller::HTTP_BAD_REQUEST);
				return;
			}
	
			$updated = $this->trf->update($id, $input);
	
			if ($updated) {
				$res = [
					'error' => false,
					'message' => 'Successfully updated the record',
				];
			} else {
				$res = [
					'error' => true,
					'message' => 'Failed to update record. Record not found.',
				];
			}
		} else {
			$res = [
				'error' => true,
				'message' => 'ID is required for update',
			];
		}
	
		$this->response($res, REST_Controller::HTTP_OK);
	}
	
	
    public function index_delete()
    {
        // Obtém o ID da URL ou do parâmetro da requisição DELETE
        $id = $this->input->get('id'); 

        if ($id) {
            $deleted = $this->trf->delete($id);

            if ($deleted) {
                $res = [
                    'error' => false,
                    'message' => 'Delete success',
                ];
            } else {
                $res = [
                    'error' => true,
                    'message' => 'Delete failed. Record not found.',
                ];
            }
        } else {
            $res = [
                'error' => true,
                'message' => 'ID is required for deletion',
            ];
        }

        $this->response($res, REST_Controller::HTTP_OK);
    }
}
