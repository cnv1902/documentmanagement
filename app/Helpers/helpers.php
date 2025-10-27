<?php

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     */
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('getFileIcon')) {
    /**
     * Get file icon class based on mime type
     */
    function getFileIcon($mimeType)
    {
        $icons = [
            // Documents
            'application/pdf' => 'las la-file-pdf',
            'application/msword' => 'las la-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'las la-file-word',
            'application/vnd.ms-excel' => 'las la-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'las la-file-excel',
            'application/vnd.ms-powerpoint' => 'las la-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'las la-file-powerpoint',
            'text/plain' => 'las la-file-alt',
            
            // Archives
            'application/zip' => 'las la-file-archive',
            'application/x-rar-compressed' => 'las la-file-archive',
            'application/x-7z-compressed' => 'las la-file-archive',
            
            // Code
            'text/html' => 'las la-file-code',
            'text/css' => 'las la-file-code',
            'application/javascript' => 'las la-file-code',
            'text/x-php' => 'las la-file-code',
            'application/json' => 'las la-file-code',
        ];

        // Check for image types
        if (strpos($mimeType, 'image/') === 0) {
            return 'las la-file-image';
        }

        // Check for video types
        if (strpos($mimeType, 'video/') === 0) {
            return 'las la-file-video';
        }

        // Check for audio types
        if (strpos($mimeType, 'audio/') === 0) {
            return 'las la-file-audio';
        }

        return $icons[$mimeType] ?? 'las la-file';
    }
}

if (!function_exists('getFileType')) {
    /**
     * Get file type category
     */
    function getFileType($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        }

        if (strpos($mimeType, 'video/') === 0) {
            return 'video';
        }

        if (strpos($mimeType, 'audio/') === 0) {
            return 'audio';
        }

        $documents = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ];

        if (in_array($mimeType, $documents)) {
            return 'document';
        }

        return 'other';
    }
}
