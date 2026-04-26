<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case WAREHOUSE_MANAGER = 'warehouse_manager';
    case HOSPITAL_MANAGER = 'hospital_manager';
    case DEPARTMENT_HEAD = 'department_head';
    case PURCHASE_COMMITTEE = 'purchase_committee';
}
