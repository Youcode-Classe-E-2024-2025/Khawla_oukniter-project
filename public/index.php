<?php
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/config/init.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;

$router = new Router();

// Route pour la page d'accueil
$router->addRoute('GET', '/', [HomeController::class, 'index']);

// Routes pour l'authentification
$router->addRoute('GET', '/login', [AuthController::class, 'loginForm']);
$router->addRoute('POST', '/login', [AuthController::class, 'login']);
$router->addRoute('GET', '/register', [AuthController::class, 'registerForm']);
$router->addRoute('POST', '/register', [AuthController::class, 'register']);
$router->addRoute('GET', '/choose_role', [AuthController::class, 'chooseRoleForm']);
$router->addRoute('POST', '/choose_role', [AuthController::class, 'chooseRole']);
$router->addRoute('GET', '/logout', [AuthController::class, 'logout']);

// Routes pour le tableau de bord
$router->addRoute('GET', '/dashboard', [DashboardController::class, 'index']);

// Routes pour les projets
$router->addRoute('GET', '/projects', [ProjectController::class, 'index']);
$router->addRoute('GET', '/projects/create', [ProjectController::class, 'create']);
$router->addRoute('POST', '/projects/create', [ProjectController::class, 'create']);
$router->addRoute('POST', '/projects', [ProjectController::class, 'store']);
$router->addRoute('GET', '/projects/{id}', [ProjectController::class, 'show']);
$router->addRoute('GET', '/projects/{id}/edit', [ProjectController::class, 'edit']);
$router->addRoute('POST', '/projects/{id}/edit', [ProjectController::class, 'update']);
$router->addRoute('POST', '/projects/{id}/invite', [ProjectController::class, 'inviteMember']);
$router->addRoute('POST', '/projects/{id}/delete', [ProjectController::class, 'delete']);
$router->addRoute('POST', '/projects/{id}/members', [ProjectController::class, 'addMember']);
$router->addRoute('POST', '/projects/{id}/members/{userId}/remove', [ProjectController::class, 'removeMember']);
$router->addRoute('GET', '/projects/{id}/tasks', [ProjectController::class, 'showTasks']);

// Routes pour les tâches
$router->addRoute('GET', '/projects/{id}/tasks', [TaskController::class, 'index']);
$router->addRoute('GET', '/projects/{projectId}/tasks/create', [TaskController::class, 'create']);
$router->addRoute('GET', '/projects/{id}/tasks/create', [TaskController::class, 'create']);
$router->addRoute('POST', '/projects/{id}/tasks', [TaskController::class, 'store']);
$router->addRoute('GET', '/tasks/{id}', [TaskController::class, 'show']);
$router->addRoute('GET', 'projects/{projectId}/tasks/{taskId}/edit', [TaskController::class, 'edit']);
$router->addRoute('POST', '/tasks/{id}/', [TaskController::class, 'update']);
$router->addRoute('POST', '/tasks/{id}/delete', [TaskController::class, 'delete']);
$router->addRoute('POST', '/projects/{projectId}/tasks/{taskId}/update_status', [TaskController::class, 'updateStatus']);
$router->addRoute('POST', '/projects/{projectId}/tasks/{taskId}/delete', [TaskController::class, 'delete']);
$router->addRoute('POST', '/projects/{projectId}/tasks/{taskId}/delete', [TaskController::class, 'delete']);
$router->addRoute('POST', '/projects/{projectId}/tasks/{taskId}/assign', [TaskController::class, 'assign']);

// Routes pour les catégories
$router->addRoute('GET', '/categories', [CategoryController::class, 'index']);
$router->addRoute('POST', '/categories', [CategoryController::class, 'store']);
$router->addRoute('POST', '/categories/{id}', [CategoryController::class, 'update']);
$router->addRoute('POST', '/categories/{id}/delete', [CategoryController::class, 'delete']);

// Routes pour la gestion des utilisateurs
$router->addRoute('GET', '/users', [UserController::class, 'index']);
$router->addRoute('GET', '/users/create', [UserController::class, 'create']);
$router->addRoute('POST', '/users/store', [UserController::class, 'store']);
$router->addRoute('GET', '/users/{id}/edit', [UserController::class, 'edit']);
$router->addRoute('POST', '/users/{id}/edit', [UserController::class, 'update']);
$router->addRoute('POST', '/users/{id}/update', [UserController::class, 'update']);
$router->addRoute('POST', '/users/{id}/delete', [UserController::class, 'delete']);
$router->addRoute('POST', '/users/{id}/updateRole', [UserController::class, 'updateRole']);

// Dispatch de la requête
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/Khawla_Boukniter-project/public', '', $uri);
if (empty($uri)) {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];
try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    // Affichage de l'erreur
    echo '<pre>';
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
    echo '</pre>';
}
