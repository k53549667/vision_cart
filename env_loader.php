<?php
/**
 * Environment Configuration Loader
 * Loads environment variables from .env file
 * 
 * Usage: require_once 'env_loader.php';
 *        $dbHost = env('DB_HOST', 'localhost');
 */

class EnvLoader {
    private static $loaded = false;
    private static $variables = [];

    /**
     * Load environment variables from .env file
     */
    public static function load($path = null) {
        if (self::$loaded) {
            return;
        }

        $envFile = $path ?? __DIR__ . '/.env';

        if (!file_exists($envFile)) {
            // Try .env.example as fallback in development
            $envFile = __DIR__ . '/.env.example';
            if (!file_exists($envFile)) {
                error_log('WARNING: No .env file found. Using default configuration.');
                self::$loaded = true;
                return;
            }
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=value
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove surrounding quotes
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }

                // Store in our array and set in environment
                self::$variables[$key] = $value;
                
                // Set in $_ENV and putenv for compatibility
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }

        self::$loaded = true;
    }

    /**
     * Get an environment variable
     */
    public static function get($key, $default = null) {
        // Ensure env is loaded
        if (!self::$loaded) {
            self::load();
        }

        // Check our loaded variables first
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }

        // Check $_ENV
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        // Check getenv()
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        return $default;
    }

    /**
     * Check if running in production
     */
    public static function isProduction() {
        return self::get('APP_ENV', 'development') === 'production';
    }

    /**
     * Check if debug mode is enabled
     */
    public static function isDebug() {
        return filter_var(self::get('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN);
    }
}

/**
 * Helper function to get environment variables
 * 
 * @param string $key The environment variable key
 * @param mixed $default Default value if not found
 * @return mixed
 */
function env($key, $default = null) {
    return EnvLoader::get($key, $default);
}

// Auto-load environment variables
EnvLoader::load();
