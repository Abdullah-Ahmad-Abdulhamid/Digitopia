<?php
require_once '../config/config.php';

header('Content-Type: application/json; charset=utf-8');

class ChallengesAPI {
    private $db;
    private $challenges;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->challenges = new Challenges();
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';
        
        switch ($method) {
            case 'GET':
                $this->handleGet($action);
                break;
            case 'POST':
                $this->handlePost($action);
                break;
            default:
                $this->sendError(405, 'Method not allowed');
        }
    }
    
    private function handleGet($action) {
        switch ($action) {
            case 'daily':
                $this->getDailyChallenge();
                break;
            case 'weekly':
                $this->getWeeklyChallenges();
                break;
            case 'history':
                $this->getUserHistory();
                break;
            default:
                $this->sendError(404, 'Action not found');
        }
    }
    
    private function getDailyChallenge() {
        $date = $_GET['date'] ?? null;
        $userId = isLoggedIn() ? $_SESSION['user_id'] : null;
        
        $result = $this->challenges->getDailyChallenge($date, $userId);
        
        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(404, $result['error']);
        }
    }
    
    private function getWeeklyChallenges() {
        $userId = isLoggedIn() ? $_SESSION['user_id'] : null;
        
        $result = $this->challenges->getWeeklyChallenges($userId);
        
        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(500, $result['error']);
        }
    }
    
    private function getUserHistory() {
        if (!isLoggedIn()) {
            $this->sendError(401, 'تسجيل الدخول مطلوب');
            return;
        }
        
        $limit = intval($_GET['limit'] ?? 10);
        $result = $this->challenges->getUserChallengeHistory($_SESSION['user_id'], $limit);
        
        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(500, $result['error']);
        }
    }
    
    private function handlePost($action) {
        if (!isLoggedIn()) {
            $this->sendError(401, 'تسجيل الدخول مطلوب');
            return;
        }
        
        switch ($action) {
            case 'complete':
                $this->completeChallenge();
                break;
            default:
                $this->sendError(404, 'Action not found');
        }
    }
    
    private function completeChallenge() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $challengeId = $input['challenge_id'] ?? null;
        $userResponse = $input['response'] ?? '';
        
        if (!$challengeId) {
            $this->sendError(400, 'معرف التحدي مطلوب');
            return;
        }
        
        $result = $this->challenges->completeChallenge($_SESSION['user_id'], $challengeId, $userResponse);
        
        if ($result['success']) {
            $this->sendSuccess([
                'message' => $result['message'],
                'earned_points' => $result['earned_points']
            ]);
        } else {
            $this->sendError(400, $result['error']);
        }
    }
    
    private function sendSuccess($data) {
        jsonResponse([
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ]);
    }
    
    private function sendError($code, $message) {
        jsonResponse([
            'success' => false,
            'error' => $message,
            'timestamp' => date('c')
        ], $code);
    }
}

$api = new ChallengesAPI();
$api->handleRequest();
?>
