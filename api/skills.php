<?php
require_once '../config/config.php';

header('Content-Type: application/json; charset=utf-8');

class SkillsAPI {
    private $db;
    private $skills;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->skills = new Skills();
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
            case 'categories':
                $this->getCategories();
                break;
            case 'skills':
                $this->getSkills();
                break;
            case 'skill':
                $this->getSkill();
                break;
            case 'user-progress':
                $this->getUserProgress();
                break;
            case 'search':
                $this->searchSkills();
                break;
            default:
                $this->sendError(404, 'Action not found');
        }
    }

    private function getCategories() {
        $result = $this->skills->getCategories();

        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(500, $result['error']);
        }
    }

    private function getSkills() {
        $categoryId = $_GET['category_id'] ?? null;
        $limit = intval($_GET['limit'] ?? 20);
        $offset = intval($_GET['offset'] ?? 0);
        $userId = isLoggedIn() ? $_SESSION['user_id'] : null;

        $result = $this->skills->getSkills($categoryId, $limit, $offset, $userId);

        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(500, $result['error']);
        }
    }

    private function getSkill() {
        $skillId = $_GET['id'] ?? null;

        if (!$skillId) {
            $this->sendError(400, 'معرف المهارة مطلوب');
            return;
        }

        $userId = isLoggedIn() ? $_SESSION['user_id'] : null;
        $result = $this->skills->getSkill($skillId, $userId);

        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(404, $result['error']);
        }
    }

    private function getUserProgress() {
        if (!isLoggedIn()) {
            $this->sendError(401, 'تسجيل الدخول مطلوب');
            return;
        }

        $result = $this->skills->getUserProgress($_SESSION['user_id']);

        if ($result['success']) {
            $this->sendSuccess($result['data']);
        } else {
            $this->sendError(500, $result['error']);
        }
    }

    private function searchSkills() {
        $query = $_GET['q'] ?? '';
        $limit = intval($_GET['limit'] ?? 10);

        if (empty($query)) {
            $this->sendError(400, 'استعلام البحث مطلوب');
            return;
        }

        $result = $this->skills->searchSkills($query, $limit);

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
            case 'update-progress':
                $this->updateProgress();
                break;
            case 'rate-skill':
                $this->rateSkill();
                break;
            default:
                $this->sendError(404, 'Action not found');
        }
    }

    private function updateProgress() {
        $input = json_decode(file_get_contents('php://input'), true);

        $skillId = $input['skill_id'] ?? null;
        $progress = floatval($input['progress'] ?? 0);
        $timeSpent = intval($input['time_spent'] ?? 0);

        if (!$skillId || $progress < 0 || $progress > 100) {
            $this->sendError(400, 'بيانات غير صحيحة');
            return;
        }

        $result = $this->skills->updateProgress($_SESSION['user_id'], $skillId, $progress, $timeSpent);

        if ($result['success']) {
            $this->sendSuccess(['message' => $result['message']]);
        } else {
            $this->sendError(500, $result['error']);
        }
    }

    private function rateSkill() {
        $input = json_decode(file_get_contents('php://input'), true);

        $skillId = $input['skill_id'] ?? null;
        $rating = intval($input['rating'] ?? 0);

        if (!$skillId || $rating < 1 || $rating > 5) {
            $this->sendError(400, 'بيانات غير صحيحة');
            return;
        }

        $result = $this->skills->rateSkill($_SESSION['user_id'], $skillId, $rating);

        if ($result['success']) {
            $this->sendSuccess(['message' => $result['message']]);
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

$api = new SkillsAPI();
$api->handleRequest();
?>
