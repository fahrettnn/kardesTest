<?php

namespace App\Core\Http;

class Route {
    private static function getUrl($index = null) {
        if ($index === null) {
            return isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        }
        
        $url = explode('/', isset($_GET['url']) ? rtrim($_GET['url'], '/') : '');
        return isset($url[$index]) ? $url[$index] : '';
    }

    public static function is($path) {
        return self::getUrl() === $path;
    }

    public static function segment($index) {
        return self::getUrl($index);
    }

    public static function matches($pattern) {
        $currentUrl = self::getUrl();
        return preg_match('#^' . str_replace('*', '.*', $pattern) . '$#', $currentUrl);
    }

    public static function hasSegment($index) {
        return !empty(self::getUrl($index));
    }

    public static function hasQueryParam(string $key, ?string $value = null): bool {
        if (!isset($_GET[$key])) {
            return false;
        }
    
        if ($value !== null) {
            return $_GET[$key] === $value;
        }
    
        return true;
    }

    /**
     * Tanımlanmış route'ları HTTP metodlarına göre saklayan dizi.
     */
    private static array $routes = [];
    private static string $basePath = '/';
    private static ?string $currentUri = null;
    private static ?string $currentMethod = null;

    // Rota eklemeyi merkezi hale getiren fonksiyon
    private static function addRoute(string $method, string $route, callable|array $action, ?string $group = null): void
    {
        $route = self::$basePath . trim($route, '/');
        self::$routes[] = [
            'method' => strtoupper($method),
            'route' => self::prepareRoute($route),
            'action' => $action,
            'group' => $group,
        ];
    }

    // GET, POST, PUT, DELETE metotları
    public static function get(string $route, callable|array $action, ?string $group = null): void
    {
        self::addRoute('GET', $route, $action, $group);
    }

    public static function post(string $route, callable|array $action, ?string $group = null): void
    {
        self::addRoute('POST', $route, $action, $group);
    }

    public static function put(string $route, callable|array $action, ?string $group = null): void
    {
        self::addRoute('PUT', $route, $action, $group);
    }

    public static function delete(string $route, callable|array $action, ?string $group = null): void
    {
        self::addRoute('DELETE', $route, $action, $group);
    }

    // Rota gruplaması
    public static function group(string $group, callable $callback): void
    {
        $callback(new class($group) {
            private string $group;
            public function __construct(string $group)
            {
                $this->group = $group;
            }

            public function get(string $route, callable|array $action): void
            {
                Route::get($route, $action, $this->group);
            }

            public function post(string $route, callable|array $action): void
            {
                Route::post($route, $action, $this->group);
            }

            public function put(string $route, callable|array $action): void
            {
                Route::put($route, $action, $this->group);
            }

            public function delete(string $route, callable|array $action): void
            {
                Route::delete($route, $action, $this->group);
            }
        });
    }

    // Dispatch işlemi
    public static function dispatch(?string $group = null): void
    {
        $requestUri = self::getUri();
        $requestMethod = self::getMethod();
    
        foreach (self::$routes as $route) {
            if ($route['method'] === strtoupper($requestMethod) &&
                (!$group || $route['group'] === $group) &&
                preg_match($route['route'], $requestUri, $matches)) {
                
                array_shift($matches); // Parametreleri al
                if (is_callable($route['action'])) {
                    call_user_func_array($route['action'], $matches);
                } elseif (is_array($route['action'])) {
                    [$class, $method] = $route['action'];
                    if (class_exists($class) && method_exists($class, $method)) {
                        call_user_func_array([new $class, $method], $matches);
                    }
                }
                return;
            }
        }
    
        // 404 yanıtı
        //self::handleError(404);
        //(new Response())->error('End Point Not Found', 404)->send();
    }
    
    // Route hazırlanması (dinamik parametreler için)
    private static function prepareRoute(string $route): string
    {
        $preparedRoute = '/^' . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', str_replace('/', '\/', $route)) . '$/';
        return $preparedRoute;
    }

    // URI elde etme
    private static function getUri(): string
    {
        if (is_null(self::$currentUri)) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (strpos($uri, self::$basePath) === 0) {
                $uri = substr($uri, strlen(self::$basePath));
            }
            self::$currentUri = trim($uri, '/');
        }

        return self::$currentUri;
    }

    // Method (GET, POST vb.) elde etme
    private static function getMethod(): string
    {
        if (is_null(self::$currentMethod)) {
            self::$currentMethod = $_SERVER['REQUEST_METHOD'];
        }
        return self::$currentMethod;
    }

    // Hata yönetimi
    private static function handleError(int $code): void
    {
        http_response_code($code);
        $response = [
            'status' => $code,
            'message' => $code === 404 ? 'Endpoint bulunamadı' : 'Bilinmeyen bir hata oluştu.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // BasePath set etme
    public static function setBasePath(string $basePath): void
    {
        self::$basePath = rtrim($basePath, '/') . '/';
    }
} 