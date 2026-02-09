<?php
/**
 * Project Cleanup Script
 * Safe, idempotent script for cleaning and organizing the snoptical project
 * 
 * USAGE:
 *   php cleanup.php              - Dry run (shows what would happen)
 *   php cleanup.php --execute    - Actually perform the operations
 *   php cleanup.php --help       - Show help
 * 
 * SAFETY FEATURES:
 * - Dry run by default (no changes unless --execute flag)
 * - Creates backup before moving/deleting
 * - Detailed logging of all operations
 * - Idempotent (safe to run multiple times)
 * - Only targets known unwanted patterns
 */

// ============================================================================
// CONFIGURATION - Customize these settings
// ============================================================================

$config = [
    // Project root directory
    'root' => __DIR__,
    
    // Backup directory for deleted files (relative to root)
    'backup_dir' => '_cleanup_backup_' . date('Y-m-d_His'),
    
    // Patterns for files to DELETE (glob patterns)
    'delete_patterns' => [
        // Temporary files
        '*.tmp',
        '*.temp',
        '*.bak',
        '*.backup',
        '*~',
        
        // OS generated files
        'Thumbs.db',
        '.DS_Store',
        'desktop.ini',
        
        // Editor temp files
        '*.swp',
        '*.swo',
        '.*.swp',
        
        // PHP error logs (but keep main logs)
        'error_log',
        
        // Compiled Python files (shouldn't be here anyway)
        '*.pyc',
        '__pycache__',
    ],
    
    // Directories to skip entirely (won't scan or modify)
    'skip_directories' => [
        '.git',
        'node_modules',
        'vendor',
        '_cleanup_backup_*',
    ],
    
    // File mapping: files that should be in specific folders
    // Format: 'filename' => 'correct_folder' (relative to root)
    'correct_locations' => [
        // Admin JS files should be in admin/admin-js, not root js/
        'admin-script.js' => 'admin/admin-js',
        'admin-script-new.js' => 'admin/admin-js',
        'admin-purchases-enhanced.js' => 'admin/admin-js',
    ],
    
    // Files that are allowed to exist in multiple locations (duplicates OK)
    'allowed_duplicates' => [
        // None currently - all files should have single canonical location
    ],
    
    // Files/folders that should NEVER be deleted (safety list)
    'protected' => [
        'config.php',
        'index.php',
        '.env',
        '.htaccess',
        'README.md',
        'DataBase',
        'includes',
        'api',
        'admin',
        'pages',
        'css',
        'js',
        'assets',
        'setup',
    ],
];

// ============================================================================
// SCRIPT LOGIC - Do not modify below unless you know what you're doing
// ============================================================================

class ProjectCleanup {
    private $config;
    private $dryRun = true;
    private $log = [];
    private $stats = [
        'files_deleted' => 0,
        'files_moved' => 0,
        'files_skipped' => 0,
        'errors' => 0,
    ];
    
    public function __construct(array $config) {
        $this->config = $config;
    }
    
    public function setDryRun(bool $dryRun): void {
        $this->dryRun = $dryRun;
    }
    
    public function run(): void {
        $this->log("========================================");
        $this->log("Project Cleanup Script");
        $this->log("========================================");
        $this->log("Mode: " . ($this->dryRun ? "DRY RUN (no changes)" : "EXECUTE (making changes)"));
        $this->log("Root: " . $this->config['root']);
        $this->log("Time: " . date('Y-m-d H:i:s'));
        $this->log("----------------------------------------");
        
        // Step 1: Delete unwanted files
        $this->log("\n[STEP 1] Scanning for unwanted files...\n");
        $this->deleteUnwantedFiles();
        
        // Step 2: Check and fix file locations
        $this->log("\n[STEP 2] Checking file locations...\n");
        $this->checkFileLocations();
        
        // Step 3: Find and report duplicate files
        $this->log("\n[STEP 3] Checking for duplicates...\n");
        $this->findDuplicates();
        
        // Summary
        $this->printSummary();
    }
    
    private function deleteUnwantedFiles(): void {
        $root = $this->config['root'];
        $deletedFiles = [];
        
        foreach ($this->config['delete_patterns'] as $pattern) {
            $files = $this->findFiles($root, $pattern);
            
            foreach ($files as $file) {
                if ($this->isProtected($file)) {
                    $this->log("  PROTECTED: $file (skipped)");
                    $this->stats['files_skipped']++;
                    continue;
                }
                
                if ($this->shouldSkipPath($file)) {
                    continue;
                }
                
                $relativePath = $this->getRelativePath($file);
                
                if ($this->dryRun) {
                    $this->log("  WOULD DELETE: $relativePath");
                } else {
                    if ($this->safeDelete($file)) {
                        $this->log("  DELETED: $relativePath");
                        $deletedFiles[] = $relativePath;
                    }
                }
                $this->stats['files_deleted']++;
            }
        }
        
        if ($this->stats['files_deleted'] === 0) {
            $this->log("  No unwanted files found.");
        }
    }
    
    private function checkFileLocations(): void {
        $root = $this->config['root'];
        $movedFiles = [];
        
        foreach ($this->config['correct_locations'] as $filename => $correctFolder) {
            // Find all instances of this file
            $files = $this->findFiles($root, $filename);
            
            $correctPath = $root . DIRECTORY_SEPARATOR . $correctFolder . DIRECTORY_SEPARATOR . $filename;
            $correctExists = file_exists($correctPath);
            
            foreach ($files as $file) {
                $currentFolder = dirname($file);
                $expectedFolder = $root . DIRECTORY_SEPARATOR . $correctFolder;
                
                // Normalize paths for comparison
                $currentFolder = realpath($currentFolder) ?: $currentFolder;
                $expectedFolder = realpath($expectedFolder) ?: $expectedFolder;
                
                if ($currentFolder === $expectedFolder) {
                    // File is in correct location
                    continue;
                }
                
                $relativePath = $this->getRelativePath($file);
                
                if ($correctExists) {
                    // Correct version exists, this is a duplicate - delete it
                    if ($this->dryRun) {
                        $this->log("  WOULD DELETE (duplicate): $relativePath");
                        $this->log("    -> Canonical version exists at: $correctFolder/$filename");
                    } else {
                        if ($this->safeDelete($file)) {
                            $this->log("  DELETED (duplicate): $relativePath");
                        }
                    }
                    $this->stats['files_deleted']++;
                } else {
                    // No correct version, move this one
                    if ($this->dryRun) {
                        $this->log("  WOULD MOVE: $relativePath");
                        $this->log("    -> To: $correctFolder/$filename");
                    } else {
                        if ($this->safeMove($file, $correctPath)) {
                            $this->log("  MOVED: $relativePath -> $correctFolder/$filename");
                            $movedFiles[] = ['from' => $relativePath, 'to' => "$correctFolder/$filename"];
                        }
                    }
                    $this->stats['files_moved']++;
                }
            }
        }
        
        if ($this->stats['files_moved'] === 0 && empty($movedFiles)) {
            $this->log("  All files are in correct locations.");
        }
    }
    
    private function findDuplicates(): void {
        $root = $this->config['root'];
        $fileHashes = [];
        $duplicates = [];
        
        // Scan all PHP and JS files for duplicates
        $extensions = ['php', 'js', 'css'];
        
        foreach ($extensions as $ext) {
            $files = $this->findFiles($root, "*.$ext");
            
            foreach ($files as $file) {
                if ($this->shouldSkipPath($file)) {
                    continue;
                }
                
                $filename = basename($file);
                $hash = md5_file($file);
                
                $key = $filename . '_' . $hash;
                
                if (!isset($fileHashes[$filename])) {
                    $fileHashes[$filename] = [];
                }
                
                $fileHashes[$filename][] = [
                    'path' => $this->getRelativePath($file),
                    'hash' => $hash,
                    'size' => filesize($file),
                ];
            }
        }
        
        // Report files with same name but different content
        $foundDuplicates = false;
        foreach ($fileHashes as $filename => $instances) {
            if (count($instances) > 1) {
                // Check if all hashes are the same (exact duplicates)
                $uniqueHashes = array_unique(array_column($instances, 'hash'));
                
                if (count($uniqueHashes) === 1) {
                    // Exact duplicates
                    $this->log("  EXACT DUPLICATES: $filename");
                    foreach ($instances as $instance) {
                        $this->log("    - {$instance['path']} ({$instance['size']} bytes)");
                    }
                    $foundDuplicates = true;
                } else {
                    // Same name, different content
                    $this->log("  DIFFERENT VERSIONS: $filename");
                    foreach ($instances as $instance) {
                        $this->log("    - {$instance['path']} (hash: " . substr($instance['hash'], 0, 8) . "...)");
                    }
                    $foundDuplicates = true;
                }
            }
        }
        
        if (!$foundDuplicates) {
            $this->log("  No duplicate files found.");
        }
    }
    
    private function findFiles(string $directory, string $pattern): array {
        $files = [];
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                if (fnmatch($pattern, $file->getFilename())) {
                    $files[] = $file->getPathname();
                }
            }
        }
        
        return $files;
    }
    
    private function shouldSkipPath(string $path): bool {
        foreach ($this->config['skip_directories'] as $skip) {
            if (fnmatch("*/$skip/*", $path) || fnmatch("*\\$skip\\*", $path)) {
                return true;
            }
            if (fnmatch("*/$skip", $path) || fnmatch("*\\$skip", $path)) {
                return true;
            }
        }
        return false;
    }
    
    private function isProtected(string $path): bool {
        $filename = basename($path);
        $dirname = basename(dirname($path));
        
        foreach ($this->config['protected'] as $protected) {
            if ($filename === $protected || $dirname === $protected) {
                return true;
            }
        }
        return false;
    }
    
    private function getRelativePath(string $path): string {
        $root = $this->config['root'];
        if (strpos($path, $root) === 0) {
            return ltrim(substr($path, strlen($root)), DIRECTORY_SEPARATOR);
        }
        return $path;
    }
    
    private function safeDelete(string $file): bool {
        if (!file_exists($file)) {
            return true; // Already deleted
        }
        
        try {
            // Create backup first
            $backupDir = $this->config['root'] . DIRECTORY_SEPARATOR . $this->config['backup_dir'];
            $relativePath = $this->getRelativePath($file);
            $backupPath = $backupDir . DIRECTORY_SEPARATOR . $relativePath;
            
            // Create backup directory structure
            $backupFolder = dirname($backupPath);
            if (!is_dir($backupFolder)) {
                mkdir($backupFolder, 0755, true);
            }
            
            // Copy to backup
            copy($file, $backupPath);
            
            // Delete original
            return unlink($file);
        } catch (Exception $e) {
            $this->log("  ERROR deleting $file: " . $e->getMessage());
            $this->stats['errors']++;
            return false;
        }
    }
    
    private function safeMove(string $source, string $destination): bool {
        if (!file_exists($source)) {
            $this->log("  ERROR: Source file not found: $source");
            $this->stats['errors']++;
            return false;
        }
        
        try {
            // Create destination directory if needed
            $destDir = dirname($destination);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            // Create backup of source
            $backupDir = $this->config['root'] . DIRECTORY_SEPARATOR . $this->config['backup_dir'];
            $relativePath = $this->getRelativePath($source);
            $backupPath = $backupDir . DIRECTORY_SEPARATOR . $relativePath;
            
            $backupFolder = dirname($backupPath);
            if (!is_dir($backupFolder)) {
                mkdir($backupFolder, 0755, true);
            }
            copy($source, $backupPath);
            
            // Move file
            return rename($source, $destination);
        } catch (Exception $e) {
            $this->log("  ERROR moving $source: " . $e->getMessage());
            $this->stats['errors']++;
            return false;
        }
    }
    
    private function log(string $message): void {
        $this->log[] = $message;
        echo $message . "\n";
    }
    
    private function printSummary(): void {
        $this->log("\n========================================");
        $this->log("SUMMARY");
        $this->log("========================================");
        
        if ($this->dryRun) {
            $this->log("Mode: DRY RUN - No changes were made");
            $this->log("");
            $this->log("Would delete: {$this->stats['files_deleted']} file(s)");
            $this->log("Would move:   {$this->stats['files_moved']} file(s)");
            $this->log("Skipped:      {$this->stats['files_skipped']} file(s)");
            $this->log("");
            $this->log("To execute these changes, run:");
            $this->log("  php cleanup.php --execute");
        } else {
            $this->log("Mode: EXECUTED - Changes were made");
            $this->log("");
            $this->log("Deleted: {$this->stats['files_deleted']} file(s)");
            $this->log("Moved:   {$this->stats['files_moved']} file(s)");
            $this->log("Skipped: {$this->stats['files_skipped']} file(s)");
            $this->log("Errors:  {$this->stats['errors']}");
            
            if ($this->stats['files_deleted'] > 0 || $this->stats['files_moved'] > 0) {
                $this->log("");
                $this->log("Backup created at: {$this->config['backup_dir']}");
            }
        }
        
        $this->log("========================================\n");
    }
    
    public function getLog(): array {
        return $this->log;
    }
    
    public function getStats(): array {
        return $this->stats;
    }
}

// ============================================================================
// MAIN EXECUTION
// ============================================================================

// Parse command line arguments
$dryRun = true;
$showHelp = false;

if (php_sapi_name() === 'cli') {
    global $argv;
    if (in_array('--execute', $argv ?? [])) {
        $dryRun = false;
    }
    if (in_array('--help', $argv ?? []) || in_array('-h', $argv ?? [])) {
        $showHelp = true;
    }
}

if ($showHelp) {
    echo <<<HELP
Project Cleanup Script
======================

A safe, idempotent script for cleaning and organizing the snoptical project.

USAGE:
  php cleanup.php              Dry run (shows what would happen)
  php cleanup.php --execute    Actually perform the operations
  php cleanup.php --help       Show this help message

SAFETY FEATURES:
  - Dry run by default (no changes unless --execute flag)
  - Creates backup before moving/deleting
  - Detailed logging of all operations
  - Idempotent (safe to run multiple times)
  - Only targets known unwanted patterns

WHAT IT DOES:
  1. Deletes temporary and backup files (*.tmp, *.bak, Thumbs.db, etc.)
  2. Moves misplaced files to their correct folders
  3. Reports duplicate files
  4. Preserves all project code and structure

HELP;
    exit(0);
}

// Run the cleanup
$cleanup = new ProjectCleanup($config);
$cleanup->setDryRun($dryRun);
$cleanup->run();
