<?php
class SystemController extends Controller {
    private $userGate;

    public function __construct() {
        $this->userGate = new UserRepository();
    }
    public function index() {
        $users = $this->userGate->getAllUsers();
        $data = json_encode([
            'users' => $users
        ]);
        echo $data;
//        $this->show('core/home', $data);
    }

    public function contact() {
//        $this->show('core/contact');
    }

    public function create() {
//        echo 'create page form';
//        $token = bin2hex(random_bytes(16));
//        $_SESSION['csrf_token'] = $token;
//        $data = json_encode([
//            'csrf_token' => $token,
//        ]);
//        $this->show('core/create', $data);
    }

    public function store() {
        $request = (array) json_decode(file_get_contents('php://input'), true);

        // Validate input request
        if (empty($request['name'])) {
            http_response_code(422);
            echo json_encode([
                'message' => 'Name is required'
            ]);
            return false;
        }

        // Store new user to database
        $user = $this->userGate->addUser($request);
        if ($user) {
            http_response_code(201);
            echo json_encode([
                'message' => 'User created',
                'user' => $user
            ]);
        } else {
            echo json_encode([
                'message' => 'No user added'
            ]);
        }
    }

    public function view() {
        /* URI = /users/{id}/profile/ */
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $user = $this->userGate->getUserById($id);
        $data = (!$user) ?
            json_encode([
            'message' => 'No user found'
        ]) :
            json_encode([
                'user' => $user
            ]);
        if (!$user) {
            http_response_code(404);
            $this->_404();
            return false;
        }
        echo($data);
    }

    public function edit() {
        /* URI = /users/{id}/edit/ */
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];

        // Authorize request
        // Check if the user is allowed to edit/update for this id info

        $user = $this->userGate->getUserById($id);
        if (!$user) {
            http_response_code(404);
            $this->_404();
            return;
        }
        $token = bin2hex(random_bytes(16));
//        $_SESSION['csrf_token'] = $token;
        $data = json_encode([
            'user' => $user,
            'csr_token' => $token
        ]);
        echo $data;
    }

    public function update() {
        /* URI = /users/{id}/profile/ */
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $request = (array) json_decode(file_get_contents('php://input'), true);

        // Authorize request
        // Check if the user is allowed to edit/update for this id info
//        if (!$this->userGate->getUserById($id) || empty($_SESSION['csrf_token']) || $request['csrf_token'] != $_SESSION['csrf_token']) {
//            http_response_code(403);
//            $this->_403();
//            return false;
//        }
        $_SESSION['csr_token'] = '';

        // Validate input request
        if (empty($request['name'])) {
            http_response_code(422);
            echo json_encode([
                'message' => 'Name is required'
            ]);
        }

        // Update user data
        $result = $this->userGate->updateUser($id, $request);
        if ($result) {
            http_response_code(201);
            echo json_encode([
                'message' => 'User updated',
            ]);
            return true;
        } else {
            http_response_code(204);
            echo json_encode([
                'message' => 'No user updated'
            ]);
            return false;
        }
    }

    public function destroy() {
        /* URI = /users/{id}/delete/ */
        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $request = (array) json_decode(file_get_contents('php://input'), true);

        // Authorize request
//        if (!$this->userGate->getUserById($id) || empty($_SESSION['csrf_token']) || $request['csrf_token'] != $_SESSION['csrf_token']) {
//            http_response_code(403);
//            $this->_403();
//            return false;
//        }

        $result = $this->userGate->deleteUser($id);
        if ($result) {
            http_response_code(204);
            echo json_encode([
                'message' => 'User deleted'
            ]);
            return true;
        } else {
            http_response_code(500);
            echo json_encode([
                'message' => 'Internal server error'
            ]);
            return false;
        }
    }
}