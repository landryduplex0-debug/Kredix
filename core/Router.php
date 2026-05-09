<?php
/**
 * Classe Router - Routage MVC
 */
class Router
{
    private array $routes = [];

    /**
     * Enregistrer une route GET
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'method'     => 'GET',
            'path'       => $path,
            'controller' => $controller,
            'action'     => $method
        ];
    }

    /**
     * Enregistrer une route POST
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'method'     => 'POST',
            'path'       => $path,
            'controller' => $controller,
            'action'     => $method
        ];
    }

    /**
     * Dispatcher la requête
     */
    public function dispatch(): void
    {
        $url = $this->getUrl();
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            // Vérifier la méthode HTTP
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convertir le pattern de route en regex
            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $url, $matches)) {
                // Extraire les paramètres
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $params = array_values($params);

                // Instancier le contrôleur
                $controllerName = $route['controller'];
                $action = $route['action'];

                if (!class_exists($controllerName)) {
                    $this->error404();
                    return;
                }

                $controller = new $controllerName();

                if (!method_exists($controller, $action)) {
                    $this->error404();
                    return;
                }

                // Exécuter l'action
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Aucune route trouvée
        $this->error404();
    }

    /**
     * Obtenir l'URL courante
     */
    private function getUrl(): string
    {
        $url = $_GET['url'] ?? '';
        return rtrim($url, '/');
    }

    /**
     * Convertir un pattern de route en regex
     */
    private function convertToRegex(string $path): string
    {
        // Remplacer les paramètres {param} par des groupes regex nommés
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Afficher la page 404
     */
    private function error404(): void
    {
        http_response_code(404);
        if (file_exists(BASE_PATH . '/views/errors/404.php')) {
            require BASE_PATH . '/views/errors/404.php';
        } else {
            echo '<h1>404 - Page non trouvée</h1>';
        }
    }
}
