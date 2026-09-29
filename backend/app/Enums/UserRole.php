<?php

namespace App\Enums;

// Define the available user roles in the application.
//
// The system has two primary roles:
// 1. Pemohon Dokumen
// 2. Penilai Dokumen
//
// Use these values consistently throughout authentication,
// authorization, middleware, policies, seeders, and API responses.
//
// Avoid using arbitrary role strings throughout the application
// so that role-related logic remains centralized.
enum UserRole: string
{
    case PEMOHON = 'PEMOHON';
    case PENILAI = 'PENILAI';

    public function label(): string
    {
        return match ($this) {
            self::PEMOHON => 'Pemohon Dokumen',
            self::PENILAI => 'Penilai Dokumen',
        };
    }
}
