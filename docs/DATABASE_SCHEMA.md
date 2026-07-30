# 🗄️ Database Schema Documentation

## Overview

This document provides a complete database schema for the AI-Powered Portfolio Platform.

**Database:** MySQL 8.0+  
**Character Set:** utf8mb4  
**Collation:** utf8mb4_unicode_ci  
**Engine:** InnoDB

---

## Entity Relationship Diagram

```
┌─────────────┐       ┌──────────────┐       ┌─────────────┐
│    Users    │───────│    Roles     │───────│ Permissions │
└─────────────┘       └──────────────┘       └─────────────┘
      │
      │ 1:N
      ├─────────────────┬──────────────┬─────────────┐
      │                 │              │             │
      ▼                 ▼              ▼             ▼
┌──────────┐    ┌──────────┐    ┌─────────┐  ┌──────────┐
│ Projects │    │  Blogs   │    │ Messages│  │  Media   │
└──────────┘    └──────────┘    └─────────┘  └──────────┘
      │               │
      │ M:N           │ M:N
      ▼               ▼
┌──────────┐    ┌──────────┐
│   Tags   │    │Categories│
└──────────┘    └──────────┘
```

---

## Table Structures

### 1. Users & Authentication Tables

#### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role_id BIGINT UNSIGNED NULL,
    avatar VARCHAR(500) NULL,
    phone VARCHAR(50) NULL,
    bio TEXT NULL,
